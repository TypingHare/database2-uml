<?php

require_once 'minimal.php';

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

    $stmt = pdo_prepare(
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

    $stmt = pdo_prepare(
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

function create_undergraduate(string $student_id): array
{
    $stmt = pdo_prepare(
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

function create_master(string $student_id): array
{
    $stmt = pdo_prepare(
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

function create_phd(string $student_id): array
{
    $stmt = pdo_prepare(
        "
            INSERT INTO PhD (student_id, qualifier)
            VALUES (:student_id, :qualifier)
        "
    );
    $data = [
        'student_id' => $student_id,
        'qualifier' => ''
    ];
    execute($stmt, $data);

    return $data;
}

/**
 * Student account creating endpoint.
 *
 * This API Creates a student account. This includes creating an account record
 * with a "student" account type, creating a student record,
 *
 * If the student account is created successfully, redirect the user to the
 * `student_info` page.
 *
 * @api
 * @see StudentType
 * @example
 *
 *     $data = [
 *         'email' => 'user@example.com',
 *         'name' => 'Alice',
 *         'dept_name => 'example department name',
 *         'student_type' => 'undergraduate'
 *     ];
 *
 * @author James Chen
 */
handle(HttpMethod::POST, function ($data) {
    $account = create_account($data["email"], $data["password"], AccountType::STUDENT);
    $student = create_student($account["email"], $data["name"], $data["dept_name"]);
    switch ($account['student_type']) {
        case StudentType::UNDERGRADUATE:
            create_undergraduate($student['student_id']);
            break;
        case StudentType::MASTER:
            create_master($student['student_id']);
            break;
        case StudentType::PHD:
            create_phd($student['student_id']);
            break;
    }

    success('Created the account successfully.');
    redirect('student_dashboard.php', [
        'student_id' => $student["student_id"]
    ]);
});
