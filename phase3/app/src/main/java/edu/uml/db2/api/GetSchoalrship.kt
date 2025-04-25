package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.ScholarshipDto
import edu.uml.db2.common.Server
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

@OptIn(InternalSerializationApi::class)
fun getScholarship(
    studentId: String, semester: String, year: String, callback: ResponseCallback<ScholarshipDto>
) {
    Server.get(
        Endpoint.GET_SCHOLARSHIP, ScholarshipDto.serializer(), Parameters.build {
            append("studentId", studentId)
            append("semester", semester)
            append("year", year)
        }, callback
    )
}
