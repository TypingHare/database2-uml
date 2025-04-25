package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.SectionListDto
import edu.uml.db2.common.Server
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

@OptIn(InternalSerializationApi::class)
fun getStudentSectionBySemester(
    studentId: String, semester: String, year: String, callback: ResponseCallback<SectionListDto>
) {
    Server.get(
        Endpoint.GET_STUDENT_SECTION_BY_SEMESTER,
        SectionListDto.serializer(),
        Parameters.build {
            append("studentId", studentId)
            append("semester", semester)
            append("year", year)
        },
        callback
    )
}