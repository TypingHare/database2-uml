package edu.uml.db2.api

import edu.uml.db2.common.CourseHistoryDto
import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import kotlinx.serialization.InternalSerializationApi

/**
 * Gets student's course history.
 *
 * @see CourseHistoryDto
 * @author Victor Ruest
 */
 
@OptIn(InternalSerializationApi::class)
fun getCourseHistory(callback: ResponseCallback<CourseHistoryDto>) {
    Server.get(Endpoint.GET_COURSE_HISTORY, CourseHistoryDto.serializer(), callback = callback)
}