## One Pager

### Logcat

**Logcat** is a module in Android Studio that allows developers to see the logs.

```text
package:mine level:info -message~:SLF4J -tag~:HWUI -tag~:ziparchive
```

### Data Transfer Object (DTO)

Here is an example of the response of the `/api/login.php` endpoint. Responses of all endpoints should contain four keys: `status`, `url`, `message`, and `data`:

- `status`: Either `success` or `failure`.
- `url`: The request URL.
- `message`: The success or failure message about the request.
- `data`: Extra data that the client side can use. It would be where the **DTO** goes.

```json
{
    "status": "success",
    "url": "/api/login.php",
    "message": "Logged in successfully.",
    "data": {
        "email": "andrew@uml.edu",
        "type": "student",
        "student": {
            "studentId": "3149703500",
            "name": "Andrew Dodge",
            "email": "andrew@uml.edu",
            "deptName": "Miner School of Computer & Information Sciences"
        }
    }
}
```

In `edu.uml.db2.dto` resides the following class:

```kotlin
@Serializable
@InternalSerializationApi
data class Response<D>(
    val status: String, val url: String, val message: String, val data: D
)
```

It serves as a container holding the deserialized data from the response JSON string. Here, `D` is a generic type specifying the data structure of the object associated with the `data` key. For instance, in ``edu.uml.db2.dto.LoginDtoKt`, there is a `LoginDto` that corresponds to the data object in the above example:

```kotlin
@Serializable
@InternalSerializationApi
data class LoginDto(
    val email: String,
    val type: String,
    val student: StudentDto? = null,
    val instructor: InstructorDto? = null
)

@Serializable
@InternalSerializationApi
data class StudentDto(
    val studentId: String, val name: String, val email: String, val deptName: String
)

@Serializable
@InternalSerializationApi
data class InstructorDto(
    val instructorId: String
)
```

### API Endpoint (PHP)