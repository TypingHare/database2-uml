<?php

require_once 'minimal.php';

function change_password(string $email, string $new_password): void
{
    $stmt = pdo_prepare(
        "
            UPDATE account 
            SET password = :password 
            WHERE email = :email
        "
    );
    execute($stmt, ['email' => $email, 'password' => $new_password]);
}

/**
 * Change account password.
 *
 * This API
 *
 * @api
 * @example
 *
 *     $data = [
 *         'email' => 'user@example.com',
 *         'old_password' = '123456',
 *         'new_password' = '654321',
 *     ];
 *
 * @author James Chen
 */
handle(HttpMethod::POST, function ($data) {
    $email = $data["email"];
    $account = get_account_by_email($email);
    if ($account === null) {
        throw new RuntimeException(
            "Account associated with [$email] does not exist."
        );
    }

    if ($data["old_password"] !== $account["password"]) {
        throw new RuntimeException(
            "Old password does not match [$email]."
        );
    }

    change_password($email, $data["new_password"]);
    success("Changed password successfully.");
});
