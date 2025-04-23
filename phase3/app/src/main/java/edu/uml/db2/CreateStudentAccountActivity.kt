package edu.uml.db2

import android.os.Bundle
import android.util.Log
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.platform.LocalContext
import edu.uml.db2.api.createStudentAccount
import edu.uml.db2.api.getDepartmentList
import edu.uml.db2.common.StudentType
import edu.uml.db2.common.User
import edu.uml.db2.common.UserType
import edu.uml.db2.common.finishActivity
import edu.uml.db2.common.saveUser
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppDropdownSelector
import edu.uml.db2.composable.AppErrorText
import edu.uml.db2.composable.AppTextField
import edu.uml.db2.composable.AppTitle
import kotlinx.serialization.InternalSerializationApi

/**
 * This activity allows users to create a student account.
 *
 * @author James Chen
 */
class CreateStudentAccountActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()
        setContent {
            CreateStudentAccountScreen()
        }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun CreateStudentAccountScreen() {
    val context = LocalContext.current

    var email by remember { mutableStateOf("") }
    var password by remember { mutableStateOf("") }
    var name by remember { mutableStateOf("") }
    var department by remember { mutableStateOf("") }
    var studentType by remember { mutableStateOf("") }
    var errorMessage by remember { mutableStateOf("") }

    var departmentList by remember { mutableStateOf(emptyList<String>()) }
    val studentTypeList = listOf(StudentType.UNDERGRADUATE, StudentType.MASTER, StudentType.PHD)
    getDepartmentList { res, isSuccess ->
        when (isSuccess) {
            true -> departmentList = res.data!!.list.map { it.deptName }
            false -> Log.e("GET_DEPARTMENTS", res.message)
        }
    }

    AppContainer {
        AppTitle("Create an Account")
        AppTextField("Email", email) { email = it }
        AppTextField("Password", password, isPassword = true) { password = it }
        AppTextField("Name", name) { name = it }
        AppDropdownSelector(
            "Department", departmentList, department
        ) { department = it }
        AppDropdownSelector(
            "Type", studentTypeList, studentType
        ) { studentType = it }
        AppErrorText(errorMessage)
        AppButton("Create") {
            createStudentAccount(
                studentType, email, password, name, department
            ) { res, isSuccess ->
                when (isSuccess) {
                    true -> {
                        saveUser(context, User(UserType.STUDENT, res.data!!.studentId))
                        finishActivity(context)
                    }

                    false -> errorMessage = res.message
                }
            }
        }
        AppButton("Back") { finishActivity(context) }
    }
}