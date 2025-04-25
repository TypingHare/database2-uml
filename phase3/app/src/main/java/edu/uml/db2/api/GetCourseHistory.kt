package edu.uml.db2.api

import edu.uml.db2.common.CourseHistoryDto
import edu.uml.db2.common.CourseHistoryResponseDto
import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

/**
 * Gets student's course history.
 *
 * @see CourseHistoryDto
 * @author Victor Ruest
 */

@OptIn(InternalSerializationApi::class)
fun getCourseHistory(studentId: String, callback: ResponseCallback<CourseHistoryResponseDto>) {
    Server.get(
        Endpoint.GET_COURSE_HISTORY,
        CourseHistoryResponseDto.serializer(),
        Parameters.build { append("studentId", studentId) },
        callback = callback
    )
}