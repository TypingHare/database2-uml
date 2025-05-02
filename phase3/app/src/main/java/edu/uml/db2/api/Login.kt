package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.LoginDto
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

/**
 * User logins with email and password.
 *
 * @param email The email associated with account.
 * @param password The password of the account.
 * @param callback The response callback function.
 * @author James Chen
 */
@OptIn(InternalSerializationApi::class)
fun login(email: String, password: String, callback: ResponseCallback<LoginDto>) {
    Server.post(Endpoint.LOGIN, LoginDto.serializer(), Parameters.build {
        append("email", email)
        append("password", password)
    }, callback)
}