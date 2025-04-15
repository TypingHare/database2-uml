package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.InstructorDto
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

/**
 * Get the information of an instructor by their ID.
 *
 * @see InstructorDto
 * @author James Chen
 */
@OptIn(InternalSerializationApi::class)
fun getInstructor(instructorId: String, callback: ResponseCallback<InstructorDto>) {
    Server.get(
        Endpoint.GET_INSTRUCTOR,
        InstructorDto.serializer(),
        Parameters.build { append("instructorId", instructorId) },
        callback
    )
}