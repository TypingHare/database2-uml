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