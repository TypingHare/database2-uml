package edu.uml.db2.dto

import kotlinx.serialization.InternalSerializationApi
import kotlinx.serialization.Serializable

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
 * The JSON string will be deserialized into a Response object. The generic type "D" specifies the
 * structure of the object associated with the `data` key.
 *
 * @template <D> The generic type of the DTO to wrap.
 * @author James Chen
 */
@Serializable
@InternalSerializationApi
data class Response<D>(
    val status: String, val url: String, val message: String, val data: D? = null
)