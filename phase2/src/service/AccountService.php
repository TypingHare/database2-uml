<?php

namespace service;

use exception\AccountAlreadyExistException;
use exception\AccountNotFoundException;
use model\Account;
use function common\convert_records_to_models;
use function common\execute;
use function common\pdo_prepare;
use function common\populate_model;

class AccountService
{
    /**
     * Retrieves all accounts from the database.
     *
     * @return Account[] An array of Account objects.
     * @author James Chen
     */
    public function getAllAccounts(): array
    {
        $stmt = pdo_prepare("SELECT * FROM account");
        execute($stmt);

        return convert_records_to_models($stmt->fetchAll(), Account::class);
    }

    /**
     * Retrieves an account by its email address.
     *
     * This method queries the database for an account with the given email.
     * If no account is found, an AccountNotFoundException is thrown.
     *
     * @param string $email The email address to search for.
     * @return Account The account associated with the provided email.
     * @throws AccountNotFoundException If no account is found for the given
     * email.
     * @author James Chen
     */
    public function getAccountByEmail(string $email): Account
    {
        $stmt = pdo_prepare("
            SELECT * FROM account 
            WHERE email = :email 
            LIMIT 1
        ");
        execute($stmt, ["email" => $email]);

        if ($stmt->rowCount() === 0) {
            throw new AccountNotFoundException(
                'No account found for email ' . $email
            );
        }

        /** @var Account */
        return populate_model(new Account(), $stmt->fetch());
    }

    /**
     * Creates an account.
     *
     * @throws AccountAlreadyExistException If there exists an account having
     * the same email address.
     * @author James Chen
     */
    public function createAccount(string $email, string $password, string $type): Account
    {
        // Check if the email has been registered
        try {
            $this->getAccountByEmail($email);
            throw new AccountAlreadyExistException(
                'Account with email ' . $email . ' already exists'
            );
        } catch (AccountNotFoundException) {
        }

        $stmt = pdo_prepare("
            INSERT INTO account (email, password, type) 
            VALUES (:email, :password, :type)
        ");
        $data = [
            "email" => $email,
            "password" => $password,
            "type" => $type
        ];
        execute($stmt, $data);

        /** @var Account */
        return populate_model(new Account(), $data);
    }

    /**
     * Updates an account's password in the database.
     *
     * @param string $email The email address of the account to update.
     * @param string $password The new password to set.
     * @return Account The updated account object.
     * @throws AccountNotFoundException If no account with the provided email
     * exists.
     * @author James Chen
     */
    public function updateAccount(string $email, string $password): Account
    {
        $account = $this->getAccountByEmail($email);
        $account->password = $password;

        $stmt = pdo_prepare("
            UPDATE account 
            SET password = :password 
            WHERE email = :email
        ");
        execute($stmt, ["email" => $email, "password" => $password]);

        return $account;
    }
}
