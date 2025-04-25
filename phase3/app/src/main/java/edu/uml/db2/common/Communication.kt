package edu.uml.db2.common

import android.util.Log
import edu.uml.db2.BuildConfig
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
import kotlinx.serialization.Serializable
import kotlinx.serialization.json.Json

@OptIn(InternalSerializationApi::class)
typealias ResponseCallback<D> = (res: Response<D>, isSuccess: Boolean) -> Unit

/**
 * The wrapper of all data transfer objects (DTOs).
 *
 * All the APi endpoints should send back a response in the following JSON format:
 *
 *     {
 *         "status": "success",
 *         "url": "the_request_url",
 *         "message": "The message about the result."
 *         "data": {
 *             "key": "value"
 *         }
 *     }
 *
 * The JSON string will be deserialized into a Response object. The generic type `D` specifies the
 * structure of the object associated with the `data` key.
 *
 * You can access the deserializer of Response by:
 *
 *     Response.deserializer
 *
 * You can also access the deserializer of other classes that are annotated by @Serializable in the
 * same way.
 *
 * @template <D> The generic type of the DTO to wrap.
 * @author James Chen
 */
@Serializable
@InternalSerializationApi
data class Response<D>(
    val status: String, val url: String, val message: String, val data: D? = null
)

object Server {
    /**
     * The client that automatically parse the response body as JSON string.
     */
    private val client by lazy {
        HttpClient(OkHttp) {
            install(ContentNegotiation) { json() }

            engine {
                config {
                    connectTimeout(30, java.util.concurrent.TimeUnit.SECONDS)
                    readTimeout(30, java.util.concurrent.TimeUnit.SECONDS)
                    writeTimeout(30, java.util.concurrent.TimeUnit.SECONDS)
                }
            }
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
     * @param parameters The parameters that are sent along with the request. If the HTTP method is
     *                   `GET`, then these parameters are concatenated to the query string in the
     *                   URL; otherwise, these parameters are sent in the `x-www-form-urlencoded`
     *                   format.
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
                    if (parameters == null) {
                        return@request
                    }

                    when (method) {
                        HttpMethod.Get -> {
                            url {
                                parameters.forEach { key, values ->
                                    values.forEach { value ->
                                        this.parameters.append(key, value)
                                    }
                                }
                            }
                        }

                        else -> setBody(FormDataContent(parameters))
                    }

                    Log.i("URL", this.url.toString())
                }

                val response = Json.decodeFromString(
                    Response.serializer(deserializer), httpResponse.body<String>()
                )
                val (status, url, message, _) = response

                Log.i(
                    "HTTP_RESPONSE",
                    "[$method] $url ($status) \"$message\""
                )

                val isSuccess = when (response.status) {
                    ResponseStatus.SUCCESS -> true
                    ResponseStatus.ERROR -> false
                    else -> throw RuntimeException("Invalid response status: $status")
                }

                callback(response, isSuccess)
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
