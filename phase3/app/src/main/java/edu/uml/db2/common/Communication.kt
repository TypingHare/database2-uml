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

@OptIn(InternalSerializationApi::class)
typealias ResponseCallback<D> = (res: Response<D>, isSuccess: Boolean) -> Unit

object Server {
    private val client by lazy {
        HttpClient(OkHttp) {
            install(ContentNegotiation) { json() }
        }
    }

    /**
     * Sends a request to the server, and fire the callback function after receiving the response.
     *
     * @param method The HTTP method to use.
     * @param url The URL portion that will be appended to the backend root URL, which is set in the
     *            application `build.gradle.kts` file. The key of the backend root url is
     *            BACKEND_ROOT_URL, which can be found in application BuildConfig.
     * @param deserializer The deserializer of the data DTO.
     * @param parameters The post parameters that are sent along with the request. These parameters
     *                   are sent in the `x-www-form-urlencoded` format.
     * @param callback The callback function that is fired after the response is received. This
     *                 function will deserialize JSON string into a `Response` object and extract
     *                 the `status` from it. When invoking, the response object and a boolean will
     *                 be passed to the callback, where the boolean indicates if the request is
     *                 successful.
     * @see Response
     */
    @OptIn(InternalSerializationApi::class)
    private fun <D> request(
        method: HttpMethod,
        url: String,
        deserializer: KSerializer<D>,
        parameters: Parameters? = null,
        callback: ResponseCallback<D>
    ) {
        CoroutineScope(Dispatchers.IO).launch {
            try {
                val httpResponse = client.request("${BuildConfig.BACKEND_ROOT_URL}/$url") {
                    this.method = method
                    contentType(ContentType.Application.FormUrlEncoded)
                    if (parameters != null) {
                        setBody(FormDataContent(parameters))
                    }
                }
                val response = Json.decodeFromString(
                    Response.serializer(deserializer), httpResponse.body<String>()
                )

                Log.i(
                    "HTTP_RESPONSE",
                    "[$method] ${response.url} (${response.status}) \"${response.message}\""
                )

                callback(response, response.status == ResponseStatus.SUCCESS)
            } catch (ex: Exception) {
                Log.e("HTTP_ERROR", ex.toString())
            }
        }
    }

    @OptIn(InternalSerializationApi::class)
    fun <D> get(
        url: String,
        deserializer: KSerializer<D>,
        parameters: Parameters? = null,
        callback: ResponseCallback<D>
    ) = request(HttpMethod.Get, url, deserializer, parameters, callback)

    @OptIn(InternalSerializationApi::class)
    fun <D> post(
        url: String,
        deserializer: KSerializer<D>,
        parameters: Parameters? = null,
        callback: ResponseCallback<D>
    ) = request(HttpMethod.Post, url, deserializer, parameters, callback)

    @OptIn(InternalSerializationApi::class)
    fun <D> put(
        url: String,
        deserializer: KSerializer<D>,
        parameters: Parameters? = null,
        callback: ResponseCallback<D>
    ) = request(HttpMethod.Put, url, deserializer, parameters, callback)

    @OptIn(InternalSerializationApi::class)
    fun <D> delete(
        url: String,
        deserializer: KSerializer<D>,
        parameters: Parameters? = null,
        callback: ResponseCallback<D>
    ) = request(HttpMethod.Delete, url, deserializer, parameters, callback)
}
