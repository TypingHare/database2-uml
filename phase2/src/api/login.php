<?php

require_once 'minimal.php';

/**
 *
 *
 * @api
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

    switch ($account['type']) {
        case AccountType::ADMIN:
            redirect('admin_dashboard.php');
            break;
        case AccountType::INSTRUCTOR:
            redirect('instructor_dashboard.php');
            break;
        case AccountType::STUDENT:
            $student = get_student_by_email($account['email']);
            redirect('student_dashboard.php', ['student_id' => $student['student_id']]);
            break;
        default:
            throw new RuntimeException('Unknown account type: ' . $account['type']);
    }
});
