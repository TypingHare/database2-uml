package edu.uml.db2.common

import android.util.Log
import edu.uml.db2.BuildConfig
import edu.uml.db2.dto.Response
import io.ktor.client.HttpClient
import io.ktor.client.call.body
import io.ktor.client.engine.okhttp.OkHttp
import io.ktor.client.plugins.contentnegotiation.ContentNegotiation
import io.ktor.client.request.forms.FormDataContent
import io.ktor.client.request.request
import io.ktor.client.request.setBody
import io.ktor.http.ContentType
import io.ktor.http.HttpMethod
import io.ktor.http.Parameters
import io.ktor.http.contentType
import io.ktor.serialization.kotlinx.json.json
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.serialization.InternalSerializationApi
import kotlinx.serialization.KSerializer
import kotlinx.serialization.json.Json

object Server {
    private val client by lazy {
        HttpClient(OkHttp) {
            install(ContentNegotiation) { json() }
        }
    }

    /**
     * Sends a request to the server, and fire the callback function after receiving the response.
     */
    @OptIn(InternalSerializationApi::class)
    private fun <D> request(
        method: HttpMethod,
        url: String,
        parameters: Parameters,
        deserializer: KSerializer<D>,
        callback: (Response<D>, Boolean) -> Unit
    ) {
        CoroutineScope(Dispatchers.IO).launch {
            try {
                val httpResponse = client.request("${BuildConfig.BACKEND_ROOT_URL}/$url") {
                    Log.i("HTTP", "${BuildConfig.BACKEND_ROOT_URL}/$url")
                    this.method = method
                    contentType(ContentType.Application.FormUrlEncoded)
                    setBody(FormDataContent(parameters))
                }
                val response = Json.decodeFromString(
                    Response.serializer(deserializer), httpResponse.body<String>()
                )

                Log.i("HTTP_RESPONSE", "[$method] ${response.url} (${response.status}) \"${response.message}\"")

                callback(response, response.status == ResponseStatus.SUCCESS)
            } catch (ex: Exception) {
                Log.e("HTTP_ERROR", ex.toString())
            }
        }
    }

    @OptIn(InternalSerializationApi::class)
    fun <D> get(
        url: String,
        parameters: Parameters,
        deserializer: KSerializer<D>,
        callback: (Response<D>, Boolean) -> Unit
    ) = request(HttpMethod.Get, url, parameters, deserializer, callback)

    @OptIn(InternalSerializationApi::class)
    fun <D> post(
        url: String,
        parameters: Parameters,
        deserializer: KSerializer<D>,
        callback: (Response<D>, Boolean) -> Unit
    ) = request(HttpMethod.Post, url, parameters, deserializer, callback)

    @OptIn(InternalSerializationApi::class)
    fun <D> put(
        url: String,
        parameters: Parameters,
        deserializer: KSerializer<D>,
        callback: (Response<D>, Boolean) -> Unit
    ) = request(HttpMethod.Put, url, parameters, deserializer, callback)

    @OptIn(InternalSerializationApi::class)
    fun <D> delete(
        url: String,
        parameters: Parameters,
        deserializer: KSerializer<D>,
        callback: (Response<D>, Boolean) -> Unit
    ) = request(HttpMethod.Delete, url, parameters, deserializer, callback)
}
