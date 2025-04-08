<?php

require_once "../minimal.php";

/**
 * Allows users to login.
 *
 * @param_post email The email of the user account.
 * @param_post password The password of the account.
 * @api
 * @author James
 */
handle(HttpMethod::POST, function (array $data) {
    $email = $data['email'];
    $password = $data['password'];

    $account = get_account_by_email($email);
    if ($account === null) {
        error_response("Account not found.");
    }

    if ($account['password'] !== $password) {
        error_response("Wrong password.", ['email' => $email]);
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
                'instructor' => $instructor
            ]);
            // no break
        case AccountType::STUDENT:
            $student = get_student_by_email($email);
            success_response("Logged in successfully.", [
                'email' => $email,
                'type' => $account['type'],
                'student' => $student
            ]);
    }
});
