package edu.uml.db2.common

object Endpoint {
    const val LOGIN = "login.php"
    const val GET_DEPARTMENT_LIST = "get_department_list.php"
    const val CREATE_STUDENT_ACCOUNT = "create_student_account.php"
    const val GET_STUDENT = "get_student.php"
}

object ResponseStatus {
    const val SUCCESS = "success"
    const val ERROR = "error"
}

object StudentType {
    const val UNDERGRADUATE = "undergraduate"
    const val MASTER = "master"
    const val PHD = "PhD"
}

enum class UserType {
    ADMIN, INSTRUCTOR, STUDENT
}