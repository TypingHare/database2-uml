package edu.uml.db2.common

/**
 * The API endpoints (PHP files that are located in `phase2/src/api`).
 */
object Endpoint {
    const val LOGIN = "login.php"
    const val GET_DEPARTMENT_LIST = "get_department_list.php"
    const val CREATE_STUDENT_ACCOUNT = "create_student_account.php"
    const val GET_STUDENT = "get_student.php"
    const val GET_INSTRUCTOR = "get_instructor.php"
    const val GET_BILLS = "get_bills.php"
    const val GET_COURSE_HISTORY = "get_course_history.php"
    const val GET_BILL = "get_bill.php"
    const val CREATE_BILL = "create_bill.php"
    const val CREATE_SCHOLARSHIP = "create_scholarship.php"
    const val GET_COURSE_LIST = "get_course_list.php"


    
=======
    const val GET_STUDENT_ALL_BILLS = "get_student_all_bills.php"
    const val GET_STUDENT_SECTION_BY_SEMESTER = "get_student_section_by_semester.php"
    const val GET_SCHOLARSHIP = "get_scholarship.php"
    const val PAY_BILL = "pay_bill.php"
    const val REGISTER = "register.php"

}

/**
 * HTTP response status. It comes with the HTTP response body.
 *
 * @see Response
 * @author James Chen
 */
object ResponseStatus {
    const val SUCCESS = "success"
    const val ERROR = "error"
}

/**
 * The types of students.
 *
 * @author
 */
object StudentType {
    const val UNDERGRADUATE = "undergraduate"
    const val MASTER = "master"
    const val PHD = "PhD"
}

/**
 * The types of users.
 *
 * @author James Chen
 */
enum class UserType {
    ADMIN, INSTRUCTOR, STUDENT
}

/**
 * Represents the status of bills.
 *
 * @author James Chen
 */
object BillStatus {
    const val PAID = "Paid"
    const val UNPAID = "Unpaid"
    const val NOT_CREATED = "Not Created"
}

/**
 * Represents the keys in the intent extra data.
 *
 * @author James Chen
 */
object IntentKey {
    const val STUDENT_ID = "STUDENT_ID"
    const val SEMESTER = "SEMESTER"
    const val YEAR = "YEAR"
    const val COURSE_NAME = "COURSE_NAME"
    const val GRADE = "GRADE"
    const val CREDITS = "CREDITS"

    const val STUDENT_NAME = "STUDENT_NAME"
    const val AMOUNT = "AMOUNT"
    const val BILL_STATUS = "BILL_STATUS"
}