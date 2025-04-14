package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import edu.uml.db2.common.StudentDto
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

@OptIn(InternalSerializationApi::class)
fun getStudent(studentId: String, callback: ResponseCallback<StudentDto>) {
    Server.get(
        Endpoint.GET_STUDENT,
        StudentDto.serializer(),
        Parameters.build { append("studentId", studentId) },
        callback
    )
}