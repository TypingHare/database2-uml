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
import edu.uml.db2.common.finishActivity
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppCenterColumn
import edu.uml.db2.composable.AppDropdownSelector
import edu.uml.db2.composable.AppSpacedColumn
import edu.uml.db2.composable.AppTextField
import edu.uml.db2.composable.AppTitle
import kotlinx.serialization.InternalSerializationApi

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
    var selectedDepartment by remember { mutableStateOf("") }
    var selectedType by remember { mutableStateOf("") }

    val departments = listOf(
        "Miner School of Computer & Information Sciences",
        "Francis College of Engineering",
        "Zuckerberg College of Health Sciences",
        "College of Fine Arts, Humanities & Social Sciences"
    )

    val types = listOf("undergraduate", "graduate", "phd")

    AppCenterColumn {
        AppSpacedColumn(16.dp) {
            AppTitle("Create an Account")
            AppTextField("Email", email) { email = it }
            AppTextField("Password", password, isPassword = true) { password = it }
            AppTextField("Name", name) { name = it }
            AppDropdownSelector(
                "Department", departments, selectedDepartment
            ) { selectedDepartment = it }
            AppDropdownSelector(
                "Type", types, selectedType
            ) { selectedType = it }
            AppButton("Create") {}
            AppButton("Back") { finishActivity(context) }
        }
    }
}