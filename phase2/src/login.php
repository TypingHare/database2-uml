<?php

require_once 'minimal.php';

/**
 * User authentication endpoint.
 *
 * This API validates user credentials and redirects to the appropriate
 * dashboard based on account type (admin, instructor, or student). The function
 * verifies that the email exists in the system and that the provided password
 * matches the stored credentials before performing redirection.
 *
 * @api
 * @example
 *
 *     $data = [
 *        'email' => 'user@example.com',
 *        'password' => 'password123'
 *     ];
 *
 * @author James Chen
 */
handle(HttpMethod::POST, function ($data) {
    $account = get_account_by_email($data['email']);
    if ($account === null) {
        throw new RuntimeException(
            'Account with email [ ' . $data['email'] . ' ] not found.'
        );
    }

    if ($account['password'] !== $data['password']) {
        throw new RuntimeException(
            'Incorrect password for account [ ' . $account['email'] . ' ].'
        );
    }

    success("Logged in successfully.");

    // Redirect the user to different pages based on the account type
    echo $account['type'];
    switch ($account['type']) {
        case AccountType::ADMIN:
            redirect(Page::ADMIN);
            // no break
        case AccountType::INSTRUCTOR:
            $instructor = get_instructor_by_email($account['email']);
            redirect(Page::INSTRUCTOR, [
                'instructor_id' => $instructor['instructor_id']
            ]);
            // no break
        case AccountType::STUDENT:
            $student = get_student_by_email($account['email']);
            redirect(Page::STUDENT, [
                'student_id' => $student['student_id']
            ]);
            // no break
        default:
            throw new RuntimeException(
                'Unknown account type: ' . $account['type']
            );
    }
});
