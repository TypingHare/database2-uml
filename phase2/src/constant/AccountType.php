<?php

namespace constant;

use model\Account;

/**
 * Represents the account types in the `account` entity or the `Account` model.
 * @see Account
 */
class AccountType
{
    public const ADMIN = 'admin';
    public const INSTRUCTOR = 'instructor';
    public const STUDENT = 'student';
}
