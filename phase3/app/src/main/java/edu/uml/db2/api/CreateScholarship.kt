package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import edu.uml.db2.common.StudentBillDto
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

@OptIn(InternalSerializationApi::class)
fun createScholarship(
    studentId: String, semester: String, year: String, callback: ResponseCallback<StudentBillDto>
) {
    Server.post(Endpoint.CREATE_SCHOLARSHIP, StudentBillDto.serializer(), Parameters.build {
        append("studentId", studentId)
        append("semester", semester)
        append("year", year)
    }, callback)
}