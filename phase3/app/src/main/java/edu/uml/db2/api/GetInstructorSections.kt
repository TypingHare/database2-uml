package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.InstructorSectionsDto
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

/**
 * Gets instructor's sections.
 *
 * @see InstructorSectionsDto
 * @author Victor Ruest
 */
@OptIn(InternalSerializationApi::class)
fun getInstructorSections(instructorId: String, callback: ResponseCallback<InstructorSectionsDto>) {
    Server.get(
        Endpoint.GET_INSTRUCTOR_SECTIONS,
        InstructorSectionsDto.serializer(),
        Parameters.build { append("instructor_id", instructorId) },
        callback = callback
    )
}