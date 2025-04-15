<?php

require_once "../minimal.php";

/**
 * Allows users to login.
 *
 * @param_post email The email of the user account.
 * @param_post password The password of the account.
 * @api
 * @author James Chen
 */
handle(HttpMethod::POST, function (array $data) {
    $email = require_field($data, 'email');
    $password = require_field($data, 'password');

    $account = get_account_by_email($email);
    if ($account === null) {
        error_response("Account not found.");
    }

    if ($account['password'] !== $password) {
        error_response("Wrong password.");
    }

    switch ($account['type']) {
        case AccountType::ADMIN:
            success_response("Logged in successfully.", [
                'email' => $email,
                'type' => $account['type']
            ]);
            // no break
        case AccountType::INSTRUCTOR:
            $instructor = get_instructor_by_email($email);
            success_response("Logged in successfully.", [
                'email' => $email,
                'type' => $account['type'],
                'instructor_id' => $instructor['instructor_id']
            ]);
            // no break
        case AccountType::STUDENT:
            $student = get_student_by_email($email);
            success_response("Logged in successfully.", [
                'email' => $email,
                'type' => $account['type'],
                'student_id' => $student['student_id']
            ]);
    }
});
