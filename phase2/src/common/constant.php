<?php
/** @noinspection PhpUnused */
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
    public const PHD = 'PhD';
}

/**
 * Represents student class standing.
 *
 * @author James Chen
 */
readonly class StudentClassStanding
{
    public const FRESHMAN = 'freshman';
    public const SOPHOMORE = 'sophomore';
    public const JUNIOR = 'junior';
    public const SENIOR = 'senior';
}

/**
 * Represents all pages in the application.
 *
 * @author James Chen; Victor R.; Alexis Marx
 */
readonly class Page
{
    // The error page; when the application encounters an exception, the user
    // will be redirected to this page with error message displayed
    public const ERROR = 'error.php';

    // The admin page
    public const ADMIN = 'admin.php';

    // The instructor page including instructor's information and provide
    // the following functionalities:
    public const INSTRUCTOR = 'instructor.php';

    // The student dashboard page including student's information and provide
    // the following functionalities:
    //   - Navigate to the change password page
    public const STUDENT = 'student.php';

    // User can create a student account in this page; after creating an
    // account, the user will be redirected to the student dashboard page
    public const CREATE_STUDENT_ACCOUNT = 'create_student_account.php';

    // User can change their account password in this page. After resetting the
    // password, the user will be redirected back to the dashboard page. The
    // user can also cancel the process by clicking the "cancel" button
    public const CHANGE_PASSWORD = 'change_password.php';

    // Student can edit their information, such as name and department.
    public const EDIT_STUDENT = 'edit_student.php';

    // Admin can review all sections in this page.
    public const SECTION = 'section.php';

    // Admin can create a course section in this page.
    public const CREATE_SECTION = 'create_section.php';

    // Admin can edit a course section in this page.
    public const EDIT_SECTION = 'edit_section.php';

    // Display the student's course history.
    public const COURSE_HISTORY = 'course_history.php';

    // Display a list of PhD students and their advisors.
    public const ADVISOR = 'advisor.php';

    // Edit the advisor of a PhD student.
    public const EDIT_ADVISOR = 'edit_advisor.php';

    public const ADVISEE = 'advisee.php';
}
