package edu.uml.db2

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.unit.dp
import edu.uml.db2.api.login
import edu.uml.db2.common.startActivity
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppCenterColumn
import edu.uml.db2.composable.AppErrorText
import edu.uml.db2.composable.AppSpacedColumn
import edu.uml.db2.composable.AppTextField
import edu.uml.db2.composable.AppTitle
import kotlinx.serialization.InternalSerializationApi

/**
 * Allows users to sign in to the system.
 * @see LoginScreen
 */
class LoginActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()
        setContent {
            LoginScreen()
        }
    }
}

/**
 * The `Composable` annotation marks this function as a Jetpack Compose UI function.
 * The `OptIn` annotation tells the Kotlin compiler that "I know I'm using an internal or
 * experimental API, and I'm okay with the risks."
 */
@OptIn(InternalSerializationApi::class)
@Composable
fun LoginScreen() {
    val context = LocalContext.current

    var email by remember { mutableStateOf("") }
    var password by remember { mutableStateOf("") }
    var errorMessage by remember { mutableStateOf("") }

    AppCenterColumn {
        AppSpacedColumn(16.dp) {
            AppTitle("Welcome to UMass Lowell Management System")
            AppTextField("Email", email) { email = it }
            AppTextField("Password", password, isPassword = true) { password = it }
            AppErrorText(errorMessage)
            AppButton("Login") {
                login(email, password) { response, isSuccess ->
                    when (isSuccess) {
                        true -> startActivity(context, StudentActivity::class, finish = true)
                        false -> errorMessage = response.message
                    }
                }
            }
            AppButton("Create Student Account") {
                startActivity(context, CreateStudentAccountActivity::class)
            }
        }
    }
}
