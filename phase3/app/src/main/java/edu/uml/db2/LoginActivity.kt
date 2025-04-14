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
import edu.uml.db2.api.login
import edu.uml.db2.common.LoginDto
import edu.uml.db2.common.User
import edu.uml.db2.common.UserType
import edu.uml.db2.common.getUser
import edu.uml.db2.common.saveUser
import edu.uml.db2.common.startActivity
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppErrorText
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
        setContent { LoginScreen() }
        onResume()
    }

    override fun onResume() {
        super.onResume()

        getUser(this)?.let { startDashboardActivity(it) }
    }

    /**
     * Starts the dashboard based on the type of the user.
     */
    fun startDashboardActivity(user: User) {
        val dashboardActivity = when (user.type) {
            UserType.ADMIN -> AdminActivity::class
            UserType.INSTRUCTOR -> InstructorActivity::class
            UserType.STUDENT -> StudentActivity::class
        }
        startActivity(this, dashboardActivity, finish = true)
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

    // This is a callback function that handles the login success response
    val handleLoginSuccess: (LoginDto) -> Unit = { loginDto ->
        val type = UserType.valueOf(loginDto.type.uppercase())
        val id = when (type) {
            UserType.ADMIN -> null
            UserType.STUDENT -> loginDto.studentId
            UserType.INSTRUCTOR -> loginDto.instructorId
        }

        // This would save the user to the internal storage, so that when the user open the App
        // next time, it can load the user from the storage
        val user = User(type, id)
        saveUser(context, user)

        (context as LoginActivity).startDashboardActivity(user)
    }

    // Here's the construction of UI components
    //
    // You can find a bunch of composable functions that start with "App" in the
    // `edu.uml.db2.composable` package. Since we don't need to make a fancy application in this
    // class, we will stick to the simplest and cleanest layout, and that is why these standardized
    // "app composable functions" come in handy
    //
    // NOTE: Please refer to each composable function for more information; you should be aware of
    // how they are encapsulated before using them
    AppContainer {
        AppTitle("Welcome to UMass Lowell Management System")
        AppTextField("Email", email) { email = it }
        AppTextField("Password", password, isPassword = true) { password = it }
        AppErrorText(errorMessage)
        AppButton("Login") {
            login(email, password) { res, isSuccess ->
                when (isSuccess) {
                    true -> handleLoginSuccess(res.data!!)
                    false -> errorMessage = res.message
                }
            }
        }
        AppButton("Create Student Account") {
            startActivity(context, CreateStudentAccountActivity::class)
        }
    }
}
