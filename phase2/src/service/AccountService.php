<?php

namespace service;

use model\Account;
use function common\connect_database;
use function common\convert_records_to_models;

class AccountService
{
    /**
     * Retrieves all accounts from the database.
     *
     * @return Account[] An array of Account objects.
     */
    public function getAllAccounts(): array
    {
        $pdo = connect_database();
        $records = $pdo->query("SELECT * FROM account")->fetchAll();
        return convert_records_to_models($records, Account::class);
    }
}
