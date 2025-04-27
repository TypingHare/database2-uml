<?php

require_once __DIR__ . '/../minimal.php';

/**
 * Retrieve all bills for a given student.
 *
 * @param string $student_id The ID of the student.
 * @return array An array of bill records.
 */
function get_all_bills(string $student_id): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * 
            FROM bill
            WHERE student_id = :student_id
        "
    );
    execute($stmt, ['student_id' => $student_id]);

    return $stmt->fetchAll();
}

/**
 * Retrieve a specific bill for a student based on semester and year.
 *
 * @param string $student_id The ID of the student.
 * @param string $semester The semester.
 * @param string $year The academic year.
 * @return array|null The bill record or null if not found.
 */
function get_bill(string $student_id, string $semester, string $year): array|null
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT *
            FROM bill
            WHERE student_id = :student_id
              AND semester = :semester
              AND year = :year
            LIMIT 1
        "
    );
    execute($stmt, [
        'student_id' => $student_id,
        'semester' => $semester,
        'year' => $year
    ]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}

/**
 * Mark a bill as paid for a specific student, semester, and year.
 *
 * @param string $student_id The ID of the student.
 * @param string $semester The semester.
 * @param string $year The academic year.
 * @return void
 */
function pay_bill(string $student_id, string $semester, string $year): void
{
    $stmt = pdo_instance()->prepare(
        "
            UPDATE bill
            SET status = :status
            WHERE student_id = :student_id
              AND semester = :semester
              AND year = :year
        "
    );
    execute($stmt, [
        'status' => BillStatus::PAID,
        'student_id' => $student_id,
        'semester' => $semester,
        'year' => $year
    ]);
}

/**
 * Get the number of unpaid bills for a student.
 *
 * @param string $student_id The ID of the student.
 * @return int Number of unpaid bills.
 */
function get_num_unpaid_bills(string $student_id): int
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT COUNT(*) as num_unpaid_bills 
            FROM bill
            WHERE student_id = :student_id
              AND status = :status
        "
    );
    execute($stmt, [
        'status' => BillStatus::UNPAID,
        'student_id' => $student_id
    ]);

    return intval($stmt->fetch()['num_unpaid_bills']);
}

/**
 * Retrieve scholarship information for a student for a given semester and year.
 *
 * @param string $student_id The ID of the student.
 * @param string $semester The semester.
 * @param string $year The academic year.
 * @return array|null The scholarship record or null if not found.
 */
function get_scholarship(string $student_id, string $semester, string $year): array|null
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT *
            FROM scholarship
            WHERE student_id = :student_id
              AND semester = :semester
              AND year = :year
            LIMIT 1
        "
    );
    execute($stmt, [
        'student_id' => $student_id,
        'semester' => $semester,
        'year' => $year
    ]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}

/**
 * Get students and their corresponding bill and scholarship statuses for a
 * given semester and year.
 *
 * @param string $semester The semester.
 * @param string $year The academic year.
 * @return array An array of student records with bill and scholarship
 *               information.
 */
function get_students_and_bills(string $semester, string $year): array
{
    $students = get_all_students();
    $student_bills = [];

    foreach ($students as $student) {
        $student_bill = get_student_bill($student, $semester, $year);
        $scholarship = get_scholarship($student['student_id'], $semester, $year);
        $student_bill['has_scholarship'] = $scholarship !== null;
        $student_bills[] = $student_bill;
    }

    return $student_bills;
}

/**
 * Gets a student's bill and scholarship information for a given semester and
 * year.
 *
 * This function combines the bill and scholarship information for a student
 * into a single object.
 *
 * @param array $student The student record.
 * @param string $semester The semester.
 * @param string $year The year.
 * @return array
 * @author James Chen
 */
function get_student_bill(array $student, string $semester, string $year): array
{
    $student_bill = [...$student, 'semester' => $semester, 'year' => $year];

    // Bill status
    $bill = get_bill($student['student_id'], $semester, $year);
    $student_bill['status'] = $bill === null ?
        BillStatus::NOT_CREATED :
        $bill['status'];

    // Scholarship
    $scholarship = get_scholarship($student['student_id'], $semester, $year);
    $student_bill['scholarship'] = $scholarship === null ?
        0 :
        $scholarship['scholarship'];

    return $student_bill;
}

/**
 * Create a new bill for a student for a given semester and year.
 *
 * @param string $student_id The ID of the student.
 * @param string $semester The semester.
 * @param string $year The academic year.
 * @return void
 */
function create_bill(string $student_id, string $semester, string $year): void
{
    $bill = get_bill($student_id, $semester, $year);
    if ($bill !== null) {
        return;
    }

    $stmt = pdo_instance()->prepare(
        "
            INSERT INTO bill
            (student_id, semester, year, status)
            VALUES (:student_id, :semester, :year, 'Unpaid') 
        "
    );
    execute($stmt, [
        'student_id' => $student_id,
        'semester' => $semester,
        'year' => $year
    ]);
}


/**
 * Create a scholarship record for a student based on their cumulative GPA.
 *
 * This method first finds the cumulative GPA of the student and finds the
 * scholarship it can have with the scholarship table. If the applicable
 * scholarship is greater than 0, a scholarship record will be created.
 *
 * @param string $student_id The ID of the student.
 * @param string $semester The semester.
 * @param string $year The academic year.
 * @return void
 * @throws Exception
 * @see SCHOLARSHIP_TABLE
 */
function create_scholarship(
    string $student_id,
    string $semester,
    string $year
): void {
    $scholarship = get_scholarship($student_id, $semester, $year);
    if ($scholarship !== null) {
        throw new Exception('Scholarship already exists');
    }

    $cumulative_gpa = get_cumulative_gpa($student_id);
    $rewarded_scholarship = 0;
    foreach (SCHOLARSHIP_TABLE as [$threshold, $scholarship]) {
        if ($cumulative_gpa > $threshold) {
            $rewarded_scholarship = $scholarship;
            break;
        }
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
