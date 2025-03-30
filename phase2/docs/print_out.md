## Phase 2 Print Out

### 1. Create a Student Account and Update Student Information

```php
// create_student.account.php
// Create a student and an account record, a student record, and a 
// `undergraduate`, `master`, or `PhD` record based on the student type.
// The three records are inserted in sequence within a transaction. If any of
// the record fails to be inserted, everything will be rolled back.
pdo_instance()->beginTransaction();
$account = create_account($data["email"], $data["password"], AccountType::STUDENT);
$student = create_student($account["email"], $data["name"], $data["dept_name"]);
switch ($student_type) {
    case StudentType::UNDERGRADUATE:
        create_undergraduate($student['student_id']);
        break;
    case StudentType::MASTER:
        create_master($student['student_id']);
        break;
    case StudentType::PHD:
        create_phd($student['student_id']);
        break;
    default:
        pdo_instance()->rollBack();
}
pdo_instance()->commit();
success('Created the account successfully.');

// service/student.php
function create_student(
    string $email,
    string $name,
    string $deptName,
): array {
    function generate_student_id(): string
    {
        return str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
    }

    // Generate a non-duplicate student ID
    $students = get_all_students();
    $student_ids = array_column($students, 'student_id');
    do {
        $student_id = generate_student_id();
    } while (in_array($student_id, $student_ids));

    $stmt = pdo_instance()->prepare(
        "
            INSERT INTO student (student_id, email, name, dept_name)
            VALUES (:student_id, :email, :name, :dept_name)
        "
    );
    $data = [
        "student_id" => $student_id,
        "email" => $email,
        "name" => $name,
        "dept_name" => $deptName
    ];
    execute($stmt, $data);

    return $data;
}
```

### 2. Admin Views, Creates, and Edits Sections

```php
// service/section.php
function check_section_legitimacy(
    string $semester,
    string $year,
    string $instructor_id,
    string $classroom_id,
    string $time_slot_id,
): void {
    try {
        check_time_slot($semester, $year, $time_slot_id);
        check_instructor($semester, $year, $instructor_id);
        check_classroom($semester, $year, $classroom_id, $time_slot_id);
        check_instructor_classroom($semester, $year, $instructor_id);
        check_instructor_time_slot($semester, $year, $instructor_id);
    } catch (RuntimeException $ex) {
        pdo_instance()->rollBack();
        throw new RuntimeException($ex->getMessage());
    }
}

/**
 * Checks if a given time slot in a specific semester and year has exceeded the
 * allowed number of sections. If the count exceeds the maximum allowed sections
 * per time slot, an exception is thrown.
 *
 */
function check_time_slot(
    string $semester,
    string $year,
    string $time_slot_id,
): void {
    $stmt = pdo_instance()->prepare(
        "
            SELECT COUNT(*) as num_sections
            FROM section
            WHERE semester = :semester
              AND year = :year
              AND time_slot_id = :time_slot_id
        "
    );
    execute($stmt, [
        'semester' => $semester,
        'year' => $year,
        'time_slot_id' => $time_slot_id
    ]);

    if ($stmt->fetch()['num_sections'] > 2) {
        $time_slot = get_time_slot_by_id($time_slot_id);
        throw new RuntimeException(
            "The time slot " .
            time_slot_to_string($time_slot) .
            " has already been taken by two or more sections."
        );
    }
}

/**
 * Ensures that an instructor is not assigned to multiple sections in the same
 * time slot during a specific semester and year. If the instructor is already
 * assigned to another section in the given time slot, an exception is thrown.
 */
function check_instructor(
    string $semester,
    string $year,
    string $instructor_id,
): void {
    $stmt = pdo_instance()->prepare(
        "
            SELECT COUNT(*) as num_sections
            FROM section
            WHERE semester = :semester
              AND year = :year
              AND instructor_id = :instructor_id
        "
    );
    execute($stmt, [
        'semester' => $semester,
        'year' => $year,
        'instructor_id' => $instructor_id
    ]);

    if ($stmt->fetch()['num_sections'] > 2) {
        $instructor = get_instructor_by_id($instructor_id);
        throw new RuntimeException(
            "The instructor " .
            $instructor['instructor_name'] .
            " has two or more sections in this semester."
        );
    }
}

/**
 * Ensures that a classroom is available for a given time slot in a specific
 * semester and year. If the classroom is already assigned to another section
 * in the same time slot, an exception is thrown.
 */
function check_classroom(
    string $semester,
    string $year,
    string $classroom_id,
    string $time_slot_id
): void {
    $stmt = pdo_instance()->prepare(
        "
            SELECT COUNT(*) as num_sections
            FROM section
            WHERE semester = :semester
              AND year = :year
              AND classroom_id = :classroom_id
              AND time_slot_id = :time_slot_id
        "
    );
    execute($stmt, [
        'semester' => $semester,
        'year' => $year,
        'classroom_id' => $classroom_id,
        'time_slot_id' => $time_slot_id
    ]);

    if ($stmt->fetch()['num_sections'] > 1) {
        $classroom = get_classroom_by_id($classroom_id);
        $time_slot = get_time_slot_by_id($time_slot_id);
        throw new RuntimeException(
            "The classroom " .
            classroom_to_string($classroom) .
            " has two or more sections in the " .
            time_slot_to_string($time_slot) .
            " time slot."
        );
    }
}

/**
 * Ensures that an instructor is not assigned to multiple classrooms in the same
 * semester and year. If the instructor is already teaching in another
 * classroom, an exception is thrown.
 */
function check_instructor_classroom(
    string $semester,
    string $year,
    string $instructor_id,
): void {
    $stmt = pdo_instance()->prepare(
        "
            SELECT *
            FROM section
            WHERE semester = :semester
              AND year = :year
              AND instructor_id = :instructor_id
        "
    );
    execute($stmt, [
        'semester' => $semester,
        'year' => $year,
        'instructor_id' => $instructor_id
    ]);

    $classrooms = $stmt->fetchAll();
    $classroom_ids = array_column($classrooms, 'classroom_id');
    if (!empty($classroom_ids) && count(array_unique($classroom_ids)) != 1) {
        throw new RuntimeException(
            "The instructor should take the two sections in the same classroom!"
        );
    }
}

/**
 * Checks if an instructor has one or two sections scheduled in consecutive time
 * slots.
 *
 * This function verifies whether an instructor has existing sections in the
 * given semester and year. If the instructor already has a section, it checks
 * whether the new section's time slot is on the same day and within a 15-minute
 * gap, ensuring that time slots are consecutive.
 */
function check_instructor_time_slot(
    string $semester,
    string $year,
    string $instructor_id,
): void {
    $stmt = pdo_instance()->prepare(
        "
            SELECT time_slot.*
            FROM time_slot
            JOIN section ON time_slot.time_slot_id = section.time_slot_id
            WHERE semester = :semester
              AND year = :year
              AND instructor_id = :instructor_id
        "
    );
    execute($stmt, [
        'semester' => $semester,
        'year' => $year,
        'instructor_id' => $instructor_id
    ]);

    if ($stmt->rowCount() < 2) {
        return;
    }

    if ($stmt->rowCount() > 2) {
        throw new RuntimeException(
            "The instructor is taking more than two time sections."
        );
    }

    $ts1 = $stmt->fetch();
    $ts2 = $stmt->fetch();

    // The two sections should be at the same days
    if ($ts1['day'] != $ts2['day']) {
        throw new RuntimeException(
            "The instructor time slots must be at the same days."
        );
    }

    $ts1_start_minutes = time_to_minutes($ts1['start_time']);
    $ts1_end_minutes = time_to_minutes($ts1['end_time']);
    $ts2_start_minutes = time_to_minutes($ts2['start_time']);
    $ts2_end_minutes = time_to_minutes($ts2['end_time']);

    // The two sections cannot be the same
    if (
        $ts1_start_minutes === $ts2_start_minutes ||
        $ts1_end_minutes === $ts2_end_minutes
    ) {
        throw new RuntimeException(
            "The instructor time slots are not consecutive."
        );
    }

    // The two sections should be consecutive
    if (
        abs($ts1_start_minutes - $ts2_end_minutes) > 15 &&
        abs($ts2_start_minutes - $ts1_end_minutes) > 15
    ) {
        throw new RuntimeException(
            "The instructor time slots are not consecutive."
        );
    }
}
```

### 9. Next Semester Course Suggestion

```php
// suggested_courses.php
handle(HttpMethod::POST, function (array $data) {
    $student_id = $data['student_id'];
    $semester = $data['semester'];
    $year = $data['year'];
    $selected_course_ids = $data['selected_course_ids'];

    foreach ($selected_course_ids as $selected_course_id) {
        [$course_id, $section_id] = explode(';', $selected_course_id);
        register_student($student_id, $course_id, $section_id, $semester, $year);
    }

    redirect(Page::COURSE_HISTORY, ['student_id' => $student_id]);
});

// service/degree_pathway.php
/**
 * Retrieves a list of suggested courses for a student for a given semester and
 * year.
 *
 * This function suggests courses that:
 * - Are part of the student's degree pathway.
 * - Have all prerequisite courses completed with a non-F grade.
 * - Have open sections in the specified semester and year.
 * - Have not already been completed by the student with a non-F grade.
 */
function get_suggested_courses(
    string $student_id,
    string $semester,
    string $year
): array {
    $stmt = pdo_instance()->prepare(
        "
            SELECT section.*, course.course_name, instructor.instructor_name, take.grade
            FROM degree_pathway dp
            JOIN section ON section.course_id = dp.course_id
            JOIN course ON course.course_id = dp.course_id
            JOIN instructor ON instructor.instructor_id = section.instructor_id
            LEFT JOIN take ON take.course_id = dp.course_id
                           AND take.student_id = :student_id0
            WHERE dp.dept_name = (
                SELECT dept_name
                FROM student
                WHERE student_id = :student_id1
                LIMIT 1
            )
            AND NOT EXISTS (
                SELECT 1
                FROM prereq
                WHERE prereq.course_id = dp.course_id
                  AND NOT EXISTS (
                      SELECT 1
                      FROM take
                      WHERE take.student_id = :student_id2
                        AND take.course_id = prereq.prereq_id
                        AND (take.grade IS NOT NULL OR take.grade <> 'F')
                  )
            )
            AND NOT EXISTS (
                SELECT 1
                FROM take
                WHERE take.student_id = :student_id3
                  AND take.course_id = course.course_id
                  AND take.grade IS NOT NULL
                  AND take.grade <> 'F'
            )
            AND section.semester = :semester
            AND section.year = :year
            ORDER BY IF(take.grade = 'F', 0, 1)
        "
    );
    execute($stmt, [
        'student_id0' => $student_id,
        'student_id1' => $student_id,
        'student_id2' => $student_id,
        'student_id3' => $student_id,
        'semester' => $semester,
        'year' => $year
    ]);

    return $stmt->fetchAll();
}
```

### 10. Bill System

```php
// service/bill.php
function create_scholarship(
    string $student_id,
    string $semester,
    string $year
): void {
    $scholarship = get_scholarship($student_id, $semester, $year);
    if ($scholarship !== null) {
        return;
    }

    $cumulative_gpa = get_cumulative_gpa($student_id);
    $rewarded_scholarship = 0;
    foreach (SCHOLARSHIP_TABLE as [$threshold, $scholarship]) {
        if ($cumulative_gpa > $threshold) {
            $rewarded_scholarship = $scholarship;
            break;
        }
    }

    if ($rewarded_scholarship === 0) {
        return;
    }

    $stmt = pdo_instance()->prepare(
        "
            INSERT INTO scholarship
            (student_id, semester, year, scholarship)
            VALUES (:student_id, :semester, :year, :scholarship)
        "
    );
    execute($stmt, [
        'student_id' => $student_id,
        'semester' => $semester,
        'year' => $year,
        'scholarship' => $rewarded_scholarship
    ]);
}

// service/section.php
// Get all the sections that the student takes in the specified semester.
function get_student_sections_by_semester(
    string $student_id,
    string $semester,
    string $year
): array {
    $stmt = pdo_instance()->prepare(
        "
            SELECT * 
            FROM take
            JOIN course ON take.course_id = course.course_id
            WHERE student_id = :student_id
              AND semester = :semester
              AND year = :year
        "
    );
    execute($stmt, [
        'student_id' => $student_id,
        'semester' => $semester,
        'year' => $year,
    ]);

    return $stmt->fetchAll();
}

// bill_payment.php
// Get the total credits the student takes in the selected semester, and find 
// the total tuition (each credit is worth 800 dollars). The amount that the
// student has to pay is equal to the total tuition minus scholarship granted.
$sections = get_student_sections_by_semester($student_id, $semester, $year);
$total_credits = array_sum(array_column($sections, 'credits'));
$total_tuition = $total_credits * TUITION_PER_CREDIT;
$bill = get_bill($student_id, $semester, $year);
$paid = $bill['status'] === BillStatus::PAID;
$scholarship = get_scholarship($student_id, $semester, $year);
$scholarship = $scholarship === null ? 0 : intval($scholarship['scholarship']);
$amount = $total_tuition - $scholarship;
```