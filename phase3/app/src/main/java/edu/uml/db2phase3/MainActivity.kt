package edu.uml.db2phase3

import android.os.Bundle
import android.util.Log
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.layout.*
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Modifier
import androidx.compose.ui.tooling.preview.Preview
import androidx.compose.ui.unit.dp
import edu.uml.db2phase3.ui.theme.Db2phase3Theme
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import io.ktor.client.*
import io.ktor.client.call.*
import io.ktor.client.engine.okhttp.*
import io.ktor.client.plugins.contentnegotiation.*
import io.ktor.client.request.*
import io.ktor.client.request.forms.FormDataContent
import io.ktor.http.*
import io.ktor.serialization.kotlinx.json.*
import kotlinx.serialization.InternalSerializationApi
import kotlinx.serialization.json.Json
import kotlinx.serialization.Serializable

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContent {
            Db2phase3Theme {
                InputWithButton()
            }
        }
    }
}

@Serializable
@kotlinx.serialization.InternalSerializationApi
data class LoginData(
    val email: String, val password: String, val type: String
)

@Serializable
@kotlinx.serialization.InternalSerializationApi
data class LoginDataPack(
    val status: String, val url: String, val message: String, val data: LoginData
)

@OptIn(InternalSerializationApi::class)
@Composable
fun InputWithButton(modifier: Modifier = Modifier) {
    var text by remember { mutableStateOf("") }
    var accountType by remember { mutableStateOf("") }

    // Ktor client setup
    val client = remember {
        HttpClient(OkHttp) {
            install(ContentNegotiation) {
                json(Json { ignoreUnknownKeys = true })
            }
        }
    }

    Column(
        modifier = modifier
            .fillMaxSize()
            .padding(16.dp), verticalArrangement = Arrangement.Center
    ) {
        TextField(
            value = text,
            onValueChange = { text = it },
            label = { Text("Enter your email") },
            modifier = Modifier.fillMaxWidth()
        )
        Spacer(modifier = Modifier.height(16.dp))
        Button(
            onClick = {
                CoroutineScope(Dispatchers.IO).launch {
                    try {
                        val response = client.post("http://10.0.2.2:80/login.php") {
                            contentType(ContentType.Application.FormUrlEncoded)
                            setBody(
                                FormDataContent(Parameters.build {
                                    append("email", text)
                                    append("password", "123456")
                                })
                            )
                        }
                        Log.d("RESPONSE", response.body<String>().toString());
                        val responseBody = response.body<LoginDataPack>()
                        Log.d("USER TYPE", responseBody.data.type)
                        accountType = responseBody.data.type;
                        // I want the Text field below shows "Account type: accountType"
                    } catch (e: Exception) {
                        Log.e("HTTP_ERROR", e.toString())
                    }
                }
            }, modifier = Modifier.fillMaxWidth()
        ) {
            Text("Submit")
        }
        Text(
            text = "Account type: ${accountType.ifEmpty { "N/A" }}",
            style = MaterialTheme.typography.labelLarge
        )
    }
}

@Preview(showBackground = true)
@Composable
fun InputWithButtonPreview() {
    Db2phase3Theme {
        InputWithButton()
    }
}
