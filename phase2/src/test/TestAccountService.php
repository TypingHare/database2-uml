<?php

namespace test;

use service\AccountService;
use constant\AccountType;
use function common\execute;
use function common\get_random_string;
use function common\pdo_prepare;

require_once __DIR__ . "/../common/initialize.php";

function test_account_service(): void
{
    $account_service = new AccountService();
    $email = get_random_string(16) . "@student.uml.edu";
    $password = '123456';
    $account = $account_service->createAccount($email, $password, AccountType::STUDENT);

    assert($account->email === $email);
    assert($account->password === $password);
    assert($account->type === AccountType::STUDENT);

    // Update the password
    $new_password = '654321';
    $account = $account_service->updateAccount($email, $new_password);
    assert($account->password === $new_password);

    // Delete the record
    $stmt = pdo_prepare("DELETE FROM account WHERE email = :email");
    execute($stmt, ["email" => $email]);

    echo "Passed: " . __FUNCTION__;
}

test_account_service();
