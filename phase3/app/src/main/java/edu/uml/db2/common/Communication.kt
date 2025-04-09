package edu.uml.db2.common

import android.util.Log
import edu.uml.db2.dto.Response
import io.ktor.client.HttpClient
import io.ktor.client.call.body
import io.ktor.client.request.forms.FormDataContent
import io.ktor.client.request.post
import io.ktor.client.request.setBody
import io.ktor.http.ContentType
import io.ktor.http.Parameters
import io.ktor.http.contentType
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.serialization.InternalSerializationApi
import kotlinx.serialization.KSerializer
import kotlinx.serialization.json.Json

// TODO: Move this to .env
const val BACKEND_ROOT_URL = "http://10.0.2.2:80/api"

/**
 * Sends a request to the server, and fire the callback function after receiving the response.
 */
@OptIn(InternalSerializationApi::class)
fun <D> sendRequest(
    client: HttpClient,
    url: String,
    parameters: Parameters,
    deserializer: KSerializer<Response<D>>,
    callback: (Response<D>, Boolean) -> Unit
) {
    CoroutineScope(Dispatchers.IO).launch {
        try {
            val httpResponse = client.post("${BACKEND_ROOT_URL}/$url") {
                contentType(ContentType.Application.FormUrlEncoded)
                setBody(FormDataContent(parameters))
            }
            val response = Json.decodeFromString(deserializer, httpResponse.body<String>())
            Log.i("HTTP_RESPONSE", "Status: ${response.status}")
            Log.i("HTTP_RESPONSE", "Message: ${response.message}")
            Log.i("HTTP_RESPONSE", "Data: ${response.data}")

            callback(response, response.status == "success")
        } catch (ex: Exception) {
            Log.e("HTTP_ERROR", ex.toString())
        }
    }
}