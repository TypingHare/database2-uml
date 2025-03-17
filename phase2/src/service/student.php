<?php

require_once __DIR__ . '/../minimal.php';

/**
 * Fetches all students from the database.
 *
 * @return array An array of students.
 * @author James Chen
 */
function get_all_students(): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM student 
        "
    );
    execute($stmt);

    return $stmt->fetchAll();
}

/**
 * Fetches a student record by their email
 *
 * @param string $email The student's email.
 * @return array|null A student object; null if no student is found.
 * @author James Chen
 */
function get_student_by_email(string $email): array|null
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM student
            WHERE email = :email
        "
    );
    execute($stmt, ["email" => $email]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}

/**
 * Fetches a student record by their student ID.
 *
 * @param string $student_id The student ID.
 * @return array|null A student object; null if no student is found.
 * @author James Chen
 */
function get_student_by_id(string $student_id): array|null
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM student
            WHERE student_id = :student_id
        "
    );
    execute($stmt, ["student_id" => $student_id]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}

/**
 * Creates an account with the given email and password.
 *
 * If an account with the same email already exists, a RuntimeException is
 * thrown.
 *
 * @param string $email The email address for the new account.
 * @param string $password The password for the new account.
 * @throws RuntimeException If an account with the same email already exists.
 * @author James Chen
 */
function create_account(string $email, string $password, string $type): array
{
    // Check if the email has been registered
    $account = get_account_by_email($email);
    if ($account !== null) {
        throw new RuntimeException(
            "Account with email [ $email ] already exists."
        );
    }

    $stmt = pdo_instance()->prepare(
        "
            INSERT INTO account (email, password, type) 
            VALUES (:email, :password, :type)
        "
    );
    $data = [
        "email" => $email,
        "password" => $password,
        "type" => $type
    ];
    execute($stmt, $data);

    return $data;
}

/**
 * Creates a student.
 *
 * @param string $email The email address for the new student.
 * @param string $name The name of the student.
 * @param string $deptName The name of the department the student is in.
 * @return array The student object.
 * @author James Chen
 */
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

/**
 * Creates an undergraduate record.
 *
 * @param string $student_id The student ID.
 * @return array A partial undergraduate objet.
 * @author James Chen
 */
function create_undergraduate(string $student_id): array
{
    $stmt = pdo_instance()->prepare(
        "
            INSERT INTO undergraduate (student_id, total_credits, class_standing)
            VALUES (:student_id, :total_credits, :class_standing)
        "
    );
    $data = [
        'student_id' => $student_id,
        'total_credits' => 0,
        'class_standing' => StudentClassStanding::FRESHMAN
    ];
    execute($stmt, $data);

    return $data;
}

/**
 * Creates a master record.
 *
 * @param string $student_id The student ID.
 * @return array A partial master objet.
 * @author James Chen
 */
function create_master(string $student_id): array
{
    $stmt = pdo_instance()->prepare(
        "
            INSERT INTO master (student_id, total_credits)
            VALUES (:student_id, :total_credits)
        "
    );
    $data = [
        'student_id' => $student_id,
        'total_credits' => 0
    ];
    execute($stmt, $data);

    return $data;
}

/**
 * Creates a PhD record.
 *
 * @param string $student_id The student ID.
 * @return array A partial PhD objet.
 * @author James Chen
 */
function create_phd(string $student_id): array
{
    $stmt = pdo_instance()->prepare(
        "
            INSERT INTO PhD (student_id)
            VALUES (:student_id)
        "
    );
    $data = ['student_id' => $student_id];
    execute($stmt, $data);

    return $data;
}

/**
 * Retrieves an undergraduate record by student ID.
 *
 * @param string $student_id The Student ID.
 * @return array|null An undergraduate object; or null if it does not exist.
 */
function get_undergraduate(string $student_id): array|null
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM undergraduate
            WHERE student_id = :student_id
        "
    );
    execute($stmt, ['student_id' => $student_id]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}

/**
 * Retrieves a master record by student ID.
 *
 * @param string $student_id The Student ID.
 * @return array|null A master object; or null if it does not exist.
 */
function get_master(string $student_id): array|null
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM master
            WHERE student_id = :student_id
        "
    );
    execute($stmt, ['student_id' => $student_id]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}

/**
 * Retrieves a PhD record by student ID.
 *
 * @param string $student_id The Student ID.
 * @return array|null A PhD object; or null if it does not exist.
 * @author James Chen
 */
function get_phd(string $student_id): array|null
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM PhD 
            WHERE student_id = :student_id
        "
    );
    execute($stmt, ['student_id' => $student_id]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}

/**
 * Retrieves the type of student by student ID.
 *
 * @param string $student_id The student ID.
 * @return string Either 'undergraduate', 'master', 'PhD', or an empty string.
 * @see StudentType
 * @author James Chen
 */
function get_student_type(string $student_id): string
{
    if (get_undergraduate($student_id) !== null) {
        return StudentType::UNDERGRADUATE;
    }

    if (get_master($student_id) !== null) {
        return StudentType::MASTER;
    }

    if (get_phd($student_id) !== null) {
        return StudentType::PHD;
    }

    return '';
}

function get_student_subclass(
    string $student_id,
    string $type,
): array {
    $validTypes = [
        StudentType::UNDERGRADUATE,
        StudentType::MASTER,
        StudentType::PHD,
    ];

    if (!in_array($type, $validTypes, true)) {
        throw new InvalidArgumentException("Invalid student type: $type");
    }

    $table = $type;
    $stmt = pdo_instance()->prepare(
        "
            SELECT * 
            FROM {$table}
            Where student_id = :student_id
        "
    );
    execute($stmt, ['student_id' => $student_id]);

    return $stmt->fetch();
}

function update_student_info(
    string $student_id,
    string $name,
    string $dept_name,
): void {
    $stmt = pdo_instance()->prepare(
        "
            UPDATE student
            SET name = :name, 
                dept_name = :dept_name
            WHERE student_id = :student_id
        "
    );
    execute($stmt, [
        "student_id" => $student_id,
        "name" => $name,
        "dept_name" => $dept_name
    ]);
}

function update_phd_info(
    string $student_id,
    string $proposal_defence_date,
    string $dissertation_defence_date,
): void {
    $stmt = pdo_instance()->prepare(
        "
            UPDATE PhD
            SET proposal_defence_date = :proposal_defence_date,
                dissertation_defence_date = :dissertation_defence_date
            WHERE student_id = :student_id
        "
    );
    execute($stmt, [
        "student_id" => $student_id,
        "proposal_defence_date" => $proposal_defence_date,
        "dissertation_defence_date" => $dissertation_defence_date
    ]);
}

function get_all_phd(): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM PhD
            JOIN student ON student.student_id = phd.student_id
        "
    );
    execute($stmt);

    return $stmt->fetchAll();
}

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

function get_advisors(string $student_id): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM advise
            JOIN instructor ON advise.instructor_id = instructor.instructor_id
            WHERE student_id = :student_id
        "
    );
    execute($stmt, ['student_id' => $student_id]);

    return $stmt->fetchAll();
}

function add_or_update_advisor(
    string      $student_id,
    string      $instructor_id,
    string|null $start_date,
    string|null $end_date
): array {
    $advisors = get_advisors($student_id);
    $instructor_ids = array_column($advisors, 'instructor_id');

    if (empty($start_date)) {
        throw new RuntimeException(
            "Start date cannot be empty."
        );
    }

    $data = [
        "student_id" => $student_id,
        "instructor_id" => $instructor_id,
        "start_date" => $start_date,
        "end_date" => empty($end_date) ? null : $end_date,
    ];
    $stmt = in_array($instructor_id, $instructor_ids, true) ?
        pdo_instance()->prepare(
            "
                UPDATE advise
                SET start_date = :start_date, 
                    end_date = :end_date
                WHERE student_id = :student_id
                  AND instructor_id = :instructor_id
            "
        ) :
        pdo_instance()->prepare(
            "
                INSERT INTO advise
                (student_id, instructor_id, start_date, end_date)
                VALUES (:student_id, :instructor_id, :start_date, :end_date)
            "
        );
    execute($stmt, $data);

    return $data;
}

function remove_advisors_not(
    string $student_id,
    array  $instructor_ids
): void {
    $placeholders = [];
    $params = [];

    foreach ($instructor_ids as $index => $id) {
        $paramName = "id$index";
        $placeholders[] = ":$paramName";
        $params[$paramName] = $id;
    }

    $instructor_id_placeholders_string = implode(',', $placeholders);
    $stmt = pdo_instance()->prepare(
        "
            DELETE FROM advise
            WHERE student_id = :student_id 
              AND instructor_id NOT IN ($instructor_id_placeholders_string)
        "
    );
    execute($stmt, array_merge($params, ['student_id' => $student_id]));
}

function get_all_advisees(string $instructor_id): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM advise
            JOIN student ON advise.student_id = student.student_id
            WHERE instructor_id = :instructor_id
        "
    );
    execute($stmt, ['instructor_id' => $instructor_id]);

    return $stmt->fetchAll();
}
