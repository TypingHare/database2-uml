## One Pager

### Setup

First, deploy the phase 2 code as we did before. Second, create a `phase3/backend.properties` and write into it the following content:

```ini
rootUrl=http://10.0.2.2:80/api
```

This entry will be first read by `build.gradle.kts` when the project is being built:

```kotlin
val backendPropertiesFile = rootProject.file("backend.properties")
val backendProperties = Properties().apply { load(FileInputStream(backendPropertiesFile)) }
val backendRootUrl = backendProperties["rootUrl"] as String

android {
    buildFeatures {
        buildConfig = true
    }

    buildTypes {
        debug {
            buildConfigField("String", "BACKEND_ROOT_URL", "\"$backendRootUrl\"")
            resValue("string", "BACKEND_ROOT_URL", backendRootUrl)
        }
        release {
            buildConfigField("String", "BACKEND_ROOT_URL", "\"$backendRootUrl\"")
            resValue("string", "BACKEND_ROOT_URL", backendRootUrl)
        }
    }
}
```

Later, we can access the value in the `BuildConfig` object in our app. The `BuildConfig` object is automatically created by gradle when the app is being built.

Let's call this entry the **backend root URL**. This entry is used in the `edu.uml.db2.common.Server` class located the following Kotlin file:

```
phase3/app/src/main/java/edu/uml/db2/common/Communication.kt
```

When a request is being sent, the `Server.request` function will *concatenate the backend root URL and the provided `url` argument*, and use it as the URL. For instance, if the backend root URL is `http://10.0.2.2:80/api`, and the provided URL is `login.php`, then the real URL to be used would be:

```text
http://10.0.2.2:80/api/login.php
```

This will be dispatched by the Android emulator to:

```text
http://localhost:80/api/login.php
```

on your machine. Here, `10.0.2.2` is a special IP address used when running an Android app on an emulator. It is used because the `localhost` inside the emulator refers to the emulator itself instead of the machine that runs the emulator.

> [!IMPORTANT]
>
> Configurate the backend root URL based on your environment. Suppose you are accessing the api directory via:
>
> ```text
> http://localhost:80/db2/phase2/src/api
> ```
>
> Then your backend root URL should be:
>
> ```text
> http://10.0.2.2:80/db2/phase2/src/api/
> ```

### Logcat

**Logcat** is a module in Android Studio that allows developers to see the logs. You can open Logcat by clicking the cat icon at the bottom-left of Android Studio. Replace the filter string with the following:

```text
package:mine level:info -message~:SLF4J -tag~:HWUI -tag~:ziparchive
```

### Data Transfer Object (DTO)

Here is an example of the response of the `/api/login.php` endpoint. Responses of all endpoints should contain four keys: `status`, `url`, `message`, and `data`:

- `status`: Either `success` or `error`.
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

In Phase 3, we will continue using the PHP backend developed in Phase 2. However, we now need to add endpoints that return JSON responses. To support this, I added the `success_response` and `error_response` to `phase2/src/minimal.php`. All new API endpoint functions will be placed in the `phase2/src/api` directory.

For example, the `phase2/src/api/login.php` serves as a login endpoint. It accepts a `POST` request with two post parameters: `email` and `password`. If there does not exist an account associated with the given `email`, it will execute:

```php
error_response("Account not found.");
```

Which will generate and return the following JSON string:

```json
{
    "status": "error",
    "url": "/api/login.php",
    "message": "Account not found.",
}
```

If the account type is `admin` and the password is correct, the following JSON string with be returned:

```json
{
    "status": "success",
    "url": "/api/login.php",
    "message": "Logged in successfully.",
    "data": {
        "email": "admin@uml.edu",
        "type": "admin"
    }
}
```

> [!IMPORTANT]
>
> To make sure the client side, the Android app, decodes the response string correctly, every API endpoint file should return a JSON string no matter what. To achieve this, make sure to add this at the end of the API file:
>
> ```php
> error_response("HTTP method not supported: " . $_SERVER["REQUEST_METHOD"]);
> ```

> [!NOTE]
>
> To comply with the convention, all the keys in the returned object will be converted into `upperCamelCase`. For example, `student_id` will be converted into `studentId`. This would not affect values.

### Phase 3 Workflow

1. Create an API endpoint in `phase2/src/api`.
2. Test the API endpoint using [Postman](https://www.postman.com).
3. Add the endpoint constant to `edu.uml.db2.common.Endpoint`.
4. Create Dto's in `edu.uml.db2.dto`.
5. Add functions that send a request to the backend to the `edu.uml.db2.api` package. Please refer to:
    - `edu.uml.db2.api.getDepartmentList` for a `GET` request
    - `edu.uml.db2.api.login` for a `POST` request
6. Create activities in `edu.uml.db2`.