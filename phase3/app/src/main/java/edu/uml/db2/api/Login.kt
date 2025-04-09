package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.Server
import edu.uml.db2.dto.LoginDto
import edu.uml.db2.dto.Response
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

@OptIn(InternalSerializationApi::class)
fun login(email: String, password: String, callback: (Response<LoginDto>, Boolean) -> Unit) {
    Server.post(Endpoint.LOGIN, Parameters.build {
        append("email", email)
        append("password", password)
    }, LoginDto.serializer(), callback)
}