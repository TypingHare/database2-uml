package edu.uml.db2.api

import edu.uml.db2.common.CreateStudentAccountDto
import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

/**
 * Creates a student account.
 *
 * @see CreateStudentAccountDto
 * @author James Chen
 */
@OptIn(InternalSerializationApi::class)
fun createStudentAccount(
    studentType: String,
    email: String,
    password: String,
    name: String,
    deptName: String,
    callback: ResponseCallback<CreateStudentAccountDto>
) {
    Server.post(
        Endpoint.CREATE_STUDENT_ACCOUNT, CreateStudentAccountDto.serializer(), Parameters.build {
            append("studentType", studentType)
            append("email", email)
            append("password", password)
            append("name", name)
            append("deptName", deptName)
        }, callback
    )
}
