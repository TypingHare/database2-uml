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
    // Get the context of the component that is using this composable function
    // In this case, it refers to an object of the `LoginActivity` class
    val context = LocalContext.current

    // Sets up mutable state variables
    //
    // Unlike normal variables, when these variables are changed, this composable function will be
    // re-rendered, so that the elements displayed are changed
    //
    // The value passed to `mutableStateOf` function is the initial value of variable; the initial
    // value of these three variables are empty string
    var email by remember { mutableStateOf("") }
    var password by remember { mutableStateOf("") }
    var errorMessage by remember { mutableStateOf("") }

    // Here's what this function returns: Kotlin is a functional programming language, and the last
    // value of every function is considered as the returned value
    //
    // You can find a bunch of composable functions in `edu.uml.db2.composable` that started with
    // "App". Since we don't need to make a fancy application in this class, we will stick to the
    // simplest and cleanest layout, and that is why these "app composable functions" come in handy
    //
    // NOTE: Please refer to each composable function for more information
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
