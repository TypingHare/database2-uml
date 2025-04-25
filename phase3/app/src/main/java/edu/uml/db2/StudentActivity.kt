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
import edu.uml.db2.api.getStudent
import edu.uml.db2.common.IntentKey
import edu.uml.db2.common.StudentDto
import edu.uml.db2.common.StudentType
import edu.uml.db2.common.User
import edu.uml.db2.common.getUser
import edu.uml.db2.common.removeUser
import edu.uml.db2.common.startActivity
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppCard
import edu.uml.db2.composable.AppCardRow
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppTitle
import kotlinx.serialization.InternalSerializationApi

/**
 * Student dashboard.
 *
 * @author James Chen
 */
class StudentActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()
        setContent { StudentScreen(getUser(this)!!) }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun StudentScreen(user: User) {
    val context = LocalContext.current

    var student by remember { mutableStateOf<StudentDto?>(null) }

    getStudent(user.id!!) { res, isSuccess ->
        when (isSuccess) {
            true -> student = res.data
            false -> Log.e("GET_STUDENT", res.message)
        }
    }

    AppContainer {
        AppTitle("Student Dashboard")
        student?.let { StudentCard(it) }
<<<<<<< HEAD
        AppButton("Course History") {
            startActivity(context, CourseHistoryActivity::class) {
                putExtra(IntentKey.STUDENT_ID, student?.studentId)
            }
        }
        AppButton("View Courses") {
            startActivity(context, ViewCoursesActivity::class)
        }
=======
        AppButton("View Bills") {
            startActivity(context, StudentBillsActivity::class) {
                putExtra(IntentKey.STUDENT_ID, user.id)
            }
        }
>>>>>>> dd22492 (task 5 semi-finished)
        AppButton("Sign Out") {
            removeUser(context)
            startActivity(context, LoginActivity::class, finish = true)
        }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun StudentCard(student: StudentDto) {
    AppCard {
        AppCardRow("Student ID", student.studentId)
        AppCardRow("Type", student.studentType)
        AppCardRow("Name", student.name)
        AppCardRow("Email", student.email)
        AppCardRow("Department", student.deptName)

        when (student.studentType) {
            StudentType.UNDERGRADUATE -> AppCardRow(
                "Class Standing", student.subclass.classStanding!!
            )

            StudentType.PHD -> {
                AppCardRow("Qualifier", student.subclass.qualifier ?: "N/A")
                AppCardRow(
                    "Proposal Defense Date", student.subclass.proposalDefenceDate ?: "N/A"
                )
                AppCardRow(
                    "Dissertation Defense Date", student.subclass.dissertationDefenceDate ?: "N/A"
                )
            }
        }
    }
}