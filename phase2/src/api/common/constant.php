<?php
/** @noinspection PhpMultipleClassesDeclarationsInOneFile */
/** @noinspection PhpIllegalPsrClassPathInspection */

/**
 * This file defines readonly classes containing related constants used
 * throughout the application. It centralizes configuration values to eliminate
 * magic numbers and strings, improving code maintainability and reducing the
 * risk of typos.
 */

/**
 * Represents HTTP methods.
 *
 * @see https://www.w3schools.com/tags/ref_httpmethods.asp
 * @author James Chen
 */
readonly class HttpMethod
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const DELETE = 'DELETE';
}

/**
 * Represents the account types in the `account` entity.
 *
 * @author James Chen
 */
readonly class AccountType
{
    public const ADMIN = 'admin';
    public const INSTRUCTOR = 'instructor';
    public const STUDENT = 'student';
}

/**
 * Represents the different types of students.
 *
 * @author James Chen
 */
readonly class StudentType
{
    public const UNDERGRADUATE = 'undergraduate';
    public const MASTER = 'master';
    public const PHD = 'phd';
}

readonly class StudentClassStanding
{
    public const FRESHMAN = 'freshman';
    public const SOPHOMORE = 'sophomore';
    public const JUNIOR = 'junior';
    public const SENIOR = 'senior';
}
