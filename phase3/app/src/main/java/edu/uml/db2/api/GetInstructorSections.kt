package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.InstructorSectionListDto
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

/**
 * Gets instructor's sections.
 *
 * @see InstructorSectionListDto
 * @author Victor Ruest, Alexis Marx
 */
@OptIn(InternalSerializationApi::class)
fun getInstructorSections(instructorId: String, callback: ResponseCallback<InstructorSectionListDto>) {
    Server.get(
        Endpoint.GET_INSTRUCTOR_SECTIONS,
        InstructorSectionListDto.serializer(),
        Parameters.build { append("instructor_id", instructorId) },
        callback = callback
    )
}