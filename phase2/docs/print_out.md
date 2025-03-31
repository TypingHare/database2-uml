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

### 3. Student Browse and Register

```php
// service/section.php

//Fetches all sections from the database of a specific section and year.
function get_all_sections_semester_year(string $semester, string $year): array
{
    $data = [
        "semester" => $semester,
        "year" => $year,
    ];
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM section
            LEFT JOIN instructor ON section.instructor_id = instructor.instructor_id
            LEFT JOIN time_slot ON section.time_slot_id = time_slot.time_slot_id
            LEFT JOIN classroom ON section.classroom_id = classroom.classroom_id
            WHERE section.semester = :semester AND section.year = :year
        "
    );
    execute($stmt, $data);

    return $stmt->fetchAll();
}

// Returns number of students enrolled in a particular section
function get_section_num_enrolled(
    string $course_id,
    string $section_id,
    string $semester,
    string $year
): int {
    $stmt = pdo_instance()->prepare(
        "
            SELECT COUNT(*) AS seats_filled
            FROM take
            WHERE course_id = :course_id
              AND section_id = :section_id
              AND semester = :semester
              AND year = :year
        "
    );
    execute($stmt, [
        'course_id' => $course_id,
        'section_id' => $section_id,
        'semester' => $semester,
        'year' => $year,
    ]);

    return $stmt->fetch();
}

// Determines if a section has space to be registered into
function check_section_availability(
    string $course_id,
    string $section_id,
    string $semester,
    string $year
): bool {
    
    $count = get_section_num_enrolled($course_id, $section_id, $semester, $year);

    if ($count['seats_filled'] < 15) {
        return true;
    }
    return false;
}

// service/student.php

//Registers a student in a class if possible
function register_student(
    string $student_id,
    string $course_id,
    string $section_id,
    string $semester,
    string $year
): array|null {

    $stmt = pdo_instance()->prepare("
    SELECT COUNT(*) FROM take 
    WHERE student_id = ? AND course_id = ? AND section_id = ? AND semester = ? AND year = ?
");
    $stmt->execute([$student_id, $course_id, $section_id, $semester, $year]);

    if ($stmt->fetchColumn() > 0) {
        throw new RunTimeException("You are already registered for this section.");
    }


    if (!has_taken_prerequisites($student_id, $course_id)) {
        throw new RuntimeException("The required prerequisites have not been taken.");
    }

    if (!check_section_availability($course_id, $section_id, $semester, $year)) {
        throw new RuntimeException("This section is full.");
    }

    pdo_instance()->beginTransaction();
    $stmt = pdo_instance()->prepare(
        "
            INSERT INTO take (
                student_id,
                course_id, 
                section_id, 
                semester, 
                year, 
                grade
            ) VALUES (
                :student_id,
                :course_id, 
                :section_id,
                :semester, 
                :year, 
                :grade
            )
        "
    );
    $data = [
        'student_id' => $student_id,
        'course_id' => $course_id,
        'section_id' => $section_id,
        'semester' => $semester,
        'year' => $year,
        'grade' => null,
    ];
    $stmt->execute($data);

    pdo_instance()->commit();

    return $data;

}

// Determines if a student has taken a course's prerequisites.
function has_taken_prerequisites(string $student_id, string $course_id): bool
{
    $prerequisites = get_prereqs($course_id);
    $completed_courses = get_all_completed_courses($student_id);

    foreach ($prerequisites as $prerequisite) {
        if (!in_array($prerequisite, $completed_courses)) {
            return false;
        }
    }
    return true;
}

```

### 4. Student Course History
```php
//Seperates current courses and completed courses into two seperate tables
//sum credits of completed(passed) courses
//converts letter grade to numerical value
//calculates cumulative gpa

// service/course.php

//use get_all_student_courses and display all courses with a grade(pass and fail)
function get_all_completed_courses(string $student_id): array
{
    $courses = get_all_student_courses($student_id);
    return array_filter(
        $courses,
        fn ($course) => $course['grade'] !== null
    );
}


//use get_all_student_courses and display all courses with null in the grade column
function get_all_active_courses(string $student_id): array
{
    $courses = get_all_student_courses($student_id);
    return array_filter(
        $courses,
        fn ($course) => $course['grade'] === null
    );
}
//using get_all_completed courses, this filters out failing grades(F) and then sums the rest
function get_total_credits(string $student_id): int
{
    $completed_courses = get_all_completed_courses($student_id);
    $passed = array_filter(
        $completed_courses,
        fn ($course) => $course['grade'] !== 'F'
    );
    $credits_array = array_column($passed, 'credits');
    return array_sum(array_map('intval', $credits_array));
}

//converts letter grade to numeric value for calculating cumulative gpa
function convert_letter_grade_to_number(string $letter_grade): float
{
    return [
        'A+' => 4.0,
        'A' => 3.9,
        'A-' => 3.7,
        'B+' => 3.3,
        'B' => 3.0,
        'B-' => 2.7,
        'C+' => 2.3,
        'C' => 2.0,
        'C-' => 1.7,
        'D+' => 1.3,
        'D' => 1.0,
        'D-' => 0.7,
        'F' => 0.0
    ][$letter_grade];
}

// service/student.php

//sums up total credits from completed classes
//multiply each letter grade by credits for that course and store each in array
//sum grade_array for a total grade
//divide total grade by total credits. Return 0 if total credits = 0
function get_cumulative_gpa(string $student_id): float
{
    $completed_courses = get_all_completed_courses($student_id);
    $total_credits = array_sum(
        array_map('intval', array_column($completed_courses, 'credits'))
    );

    $grade_array = array_map(
        fn ($course) => convert_letter_grade_to_number($course['grade']) * intval($course['credits']),
        $completed_courses
    );
    $total_grade = array_sum($grade_array);

    return $total_credits === 0 ? 0. : $total_grade / $total_credits;
}
```
### 5. Instructor Course History

```php
// service/section.php

// Returns all sections taught by a particular instructor, grouped so that upon displaying, the sections can be organized by course_id
function get_all_sections_instructor(string $instructor_id): array
{
    $data = [
        "instructor_id" => $instructor_id,
    ];
    $stmt = pdo_instance()->prepare(
        "
            SELECT DISTINCT course_id, section_id from section
            WHERE section.instructor_id = :instructor_id
            GROUP BY course_id, section_id
        "
    );
    execute($stmt, $data);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $sections[$row['course_id']][] = $row;
    }

    return $sections;
}

// Gets all semester/year instances of a course and section that a specific instructor has taught
function get_section_instances(
    string $instructor_id,
    string $course_id,
    string $section_id,
): array {

    $data = [
        "instructor_id" => $instructor_id,
        "course_id" => $course_id,
        "section_id" => $section_id
    ];
    $stmt = pdo_instance()->prepare(
        "
            SELECT semester, year from section
            WHERE section.instructor_id = :instructor_id AND 
            section.course_id = :course_id AND
            section.section_id = :section_id
        "
    );
    execute($stmt, $data);

    return $stmt->fetchAll();
}

// Gets the ids and grades of all students from a particular section instance
function get_section_records(
    string $course_id,
    string $section_id,
    string $semester,
    string $year
) : array {
    $data = [
        "course_id" => $course_id,
        "section_id" => $section_id,
        "semester" => $semester,
        "year" => $year
    ];
    $stmt = pdo_instance()->prepare(
        "
            SELECT student_id, grade from take
            WHERE  take.course_id = :course_id AND
            take.section_id = :section_id AND
            take.semester = :semester AND
            take.year = :year
        "
    );
    execute($stmt, $data);

    return $stmt->fetchAll();
}

```

### 6. Assign phd student as TA

```php
//choose a TA from a list of phd students
//Select section for TA to be assigned too
    //must contain more then 10 students
    //PhD student can only be assigned one section per semester
//confirm and assign TA
//view list of TAs 

// src/select_ta_section.php

//"a" is all sections with count > then 10(more than 10 students in the section)
//"b" is all years and semesters student has been a TA where semesters and years match between a and b
//a - b, no sections will appear during a semester where PhD student is already serving as a TA
function get_ta_section(string $student_id): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * 
            FROM (SELECT course_id, section_id, semester, year  
                FROM take
                GROUP BY course_id, section_id, semester, year
                HAVING COUNT(*) > 10
            ) AS a 
            WHERE NOT EXISTS ( SELECT 1
                FROM (SELECT year, semester                     
                    FROM TA
                    WHERE student_id = :student_id
                    ) AS b 
                WHERE a.semester = b.semester
                AND a.year = b.year
            );
        "
    );
    execute($stmt, ['student_id' => $student_id]);

    return $stmt->fetchAll();
}

// src/view_ta.php
//view all assigned TAs
function get_all_tas(): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * 
            FROM TA
            ORDER BY semester, year;
        "
    );
    execute($stmt);

    return $stmt->fetchAll();
}



```

### 7. Admin Assigns Graders

```php
// service/section.php

// Gets all section eligible to be assigned a grader
function get_grader_sections() : array 
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT course_id, section_id, semester, year
            FROM take
            GROUP BY course_id, section_id, semester, year
            HAVING COUNT(*) BETWEEN 5 AND 10
            AND NOT EXISTS (
                SELECT 1 FROM undergraduategrader u
                WHERE u.course_id = take.course_id
                    AND u.section_id = take.section_id
                    AND u.semester = take.semester
                    AND u.year = take.year
            )
            AND NOT EXISTS (
                SELECT 1 FROM mastergrader m
                WHERE m.course_id = take.course_id
                    AND m.section_id = take.section_id
                    AND m.semester = take.semester
                    AND m.year = take.year
            );
        "
    );
    execute($stmt);

    return $stmt->fetchAll();
}

// select_grader.php

// Gets all eligible graders for a particular course/section/semester/year
function get_possible_graders(string $course_id, string $section_id, string $semester, string $year) : array 
  {
     $stmt = pdo_instance()->prepare(
        "
          SELECT DISTINCT a.student_id
          FROM (
              SELECT student_id FROM undergraduate
              UNION
              SELECT student_id FROM master
          ) AS a
          JOIN take t ON a.student_id = t.student_id
          WHERE t.course_id = :course_id 
            AND t.section_id = :section_id 
            AND t.semester = :semester 
            AND t.year = :year
            AND t.grade IN ('A-', 'A')
            AND NOT EXISTS (
                SELECT 1 
                FROM (
                    SELECT student_id FROM undergraduategrader
                    UNION
                    SELECT student_id FROM mastergrader
                ) as b
                WHERE b.student_id = a.student_id 
                AND b.semester = t.semester 
                AND b.year = t.year
            );

        "
    );
    
    execute($stmt, [
      "course_id" => $course_id,
      "section_id" => $section_id,
      "semester" => $semester,
      "year" => $year
  ]);

    return $stmt->fetchAll();
  }

  // service/student.php

  // Adds selected grader for selected section to appropriate grader table
  function add_grader(string $student_id, string $course_id, string $section_id, string $semester, string $year): null
{
    if (get_student_type($student_id) == StudentType::UNDERGRADUATE) {
        $stmt = pdo_instance()->prepare(
            "
            INSERT INTO undergraduategrader(student_id, course_id, section_id, semester, year)
            VALUES (:student_id, :course_id, :section_id, :semester, :year); 
        "
        );
    }
    else {
        $stmt = pdo_instance()->prepare(
            "
            INSERT INTO mastergrader(student_id, course_id, section_id, semester, year)
            VALUES (:student_id, :course_id, :section_id, :semester, :year); 
        "
        );
    }
    
    execute($stmt, [
        "student_id" => $student_id,
        "course_id" => $course_id,
        "section_id" => $section_id,
        "semester" => $semester,
        "year" => $year
    ]);
}

```

### 8. Instructors Assigned as Advisors to PhD Students
```php
//Admin and instructors have ability to assign instructors as adviors to PhD students
    //1 or 2 advisors per student
    //Advising start and optional end date
    //can view student course history and update their information

// service/student.php


function get_phd_and_advisors(): array
{
    // Get all records in the `advise` table
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM advise
            JOIN student ON advise.student_id = student.student_id
            JOIN instructor ON advise.instructor_id = instructor.instructor_id
        "
    );
    execute($stmt);
    $advise_records = $stmt->fetchAll();

    return array_reduce($advise_records, function ($result, $record) {
        $result[$record['student_id']][] = $record;
        return $result;
    }, []);
}


// src/advisor.php

//arrays for table
$phd_and_advisors = get_phd_and_advisors();
$phd_having_advisors = array_keys($phd_and_advisors);
$phd_having_no_advisors = [];
foreach (get_all_phd() as $phd) {
    if (!in_array($phd['student_id'], $phd_having_advisors)) {
        $phd_having_no_advisors[] = $phd;
    }
}

//advisor records for table
function get_advisor_desc(array|null $record): string
{
    if (!$record) {
        return '';
    }

    return "{$record['instructor_name']} <br/> ({$record['start_date']} to {$record['end_date']})";
}

//table containing all phd students with and without advisors 
 <?php foreach ($phd_and_advisors as $records): ?>
          <tr>
            <td><?= $records[0]['student_id'] ?></td>
            <td><?= $records[0]['name'] ?></td>
            <td><?= get_advisor_desc($records[0] ?? null) ?></td>
            <td><?= get_advisor_desc($records[1] ?? null) ?></td>
            <td>
              <a href="<?= get_edit_url($records[0]['student_id']) ?>">
                <button>Edit</button>
              </a>
            </td>
          </tr>
        <?php endforeach ?>

        <?php foreach ($phd_having_no_advisors as $phd): ?>
          <tr>
            <td><?= $phd['student_id'] ?></td>
            <td><?= $phd['name'] ?></td>
            <td></td>
            <td></td>
            <td>
              <a href="<?= get_edit_url($phd['student_id']) ?>">
                <button>Edit</button>
              </a>
            </td>
          </tr>
        <?php endforeach ?>

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