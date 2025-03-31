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
 * Represents the status of bills.
 *
 * @author James Chen
 */
readonly class BillStatus
{
    public const PAID = 'Paid';
    public const UNPAID = 'Unpaid';
    public const NOT_CREATED = 'Not Created';
}

/**
 * To be simplified, the tuition per credit is a constant.
 *
 * @author James Chen
 */
defined('TUITION_PER_CREDIT') || define('TUITION_PER_CREDIT', 800);

/**
 * Scholarship table.
 *
 * @author James Chen
 */
defined('SCHOLARSHIP_TABLE') || define('SCHOLARSHIP_TABLE', [
    [3.98, 4000],
    [3.75, 3000],
    [3.50, 2000],
    [3.25, 500],
    [3.00, 200],
]);

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

    // This page allows the instructors to view their advisees.
    public const ADVISEE = 'advisee.php';

    // This page allows students to check all the tuition bills, including paid
    // and unpaid.
    public const STUDENT_BILLS = 'student_bills.php';

    // This page allows students to pay the bill.
    public const BILL_PAYMENT = 'bill_payment.php';

    // This page displays the payment success message.
    public const PAYMENT_SUCCESS = 'payment_success.php';

    // This page allows admin to select a specific semester for viewing bills.
    public const BILLS_SELECT_SEMESTER = 'bills_select_semester.php';

    // This page allows admin to create a bill for a student.
    public const BILLS = 'bills.php';

    // This page allows admin to select PhD student for TA role.
    public const SELECT_PHD = 'select_phd_student.php';

    // This page allows admin to select section for TA.
    public const SELECT_TA_SECTION = 'select_ta_section.php';

    //This page shows all selected info for admin to submit.
    public const ASSIGN_TA = 'assign_ta.php';

    //This page shows Admin all created TAs.
    public const VIEW_TA = 'view_ta.php';

    // This page allows a student to browse currently offered classes and register
    public const BROWSE = 'student_browse.php';

    //This page displays the status of a registration request
    public const REGISTER = 'register.php';

    //This page confirms a registration request by a student has succeeded
    public const REGISTER_SUCCESS = 'registration_success.php';

    //This page allows an instructor to view a list of all sections they've taught
    public const INSTRUCTOR_RECORDS = 'instructor_records.php';

    //This pages lists the student records and data for a single section, past and present
    public const RECORD = 'section_record.php';

    // This page displays several suggested courses that the student can sign up
    // for the next semester.
    public const SUGGESTED_COURSES = 'suggested_courses.php';

    // This page allows admin to select a section eligible to be assigned a grader
    public const SELECT_GRADER_SECTION = 'select_grader_section.php';

    // This page allows admin to select a student to be a grader for a section
    public const SELECT_GRADER = 'select_grader.php';

    // This page allows admin to assign a selected student as a grader to a selected section
    public const ASSIGN_GRADER = 'assign_grader.php';
}
