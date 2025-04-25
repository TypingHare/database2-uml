package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.RegisterDto
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

/**
 * Registers student in section
 *
 * @see RegisterDto
 * @author Alexis Marx
 */
@OptIn(InternalSerializationApi::class)
fun register(studentId: String, courseId: String, sectionId: String, callback: ResponseCallback<RegisterDto>) {
    Server.post(Endpoint.REGISTER, RegisterDto.serializer(), Parameters.build {
        append("studentId", studentId)
        append("courseId", courseId)
        append("sectionId", sectionId)
    }, callback)
}