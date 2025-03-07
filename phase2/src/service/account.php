<?php

require_once __DIR__ . '/../minimal.php';

/**
 * Retrieves an account by its email address.
 *
 * This method queries the database for an account with the given email.
 * If no account is found, null will be returned.
 *
 * @param string $email The email address to search for.
 * @return array|null An `account` record; null if no account is found.
 * @author James Chen
 */
function get_account_by_email(string $email): array|null
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT * FROM account
            WHERE email = :email
            LIMIT 1
        "
    );
    execute($stmt, ["email" => $email]);

    return $stmt->rowCount() === 0 ? null : $stmt->fetch();
}

function change_password(string $email, string $new_password): void
{
    $stmt = pdo_instance()->prepare(
        "
            UPDATE account 
            SET password = :password 
            WHERE email = :email
        "
    );
    execute($stmt, ['email' => $email, 'password' => $new_password]);
}
