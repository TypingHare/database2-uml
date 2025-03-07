<?php

require_once __DIR__ . '/../minimal.php';

/**
 * Fetches all students from the database.
 *
 * @return array An array of departments.
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
