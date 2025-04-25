package edu.uml.db2

import android.app.Activity
import android.os.Bundle
import android.util.Log
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.unit.dp
import edu.uml.db2.api.getCourseHistory
import edu.uml.db2.common.CourseHistoryDto
import edu.uml.db2.common.IntentKey
import edu.uml.db2.common.User
import edu.uml.db2.common.getUser
import edu.uml.db2.common.startActivity
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppTable
import edu.uml.db2.composable.AppTableCell
import edu.uml.db2.composable.AppText
import edu.uml.db2.composable.AppTitle
import kotlinx.serialization.InternalSerializationApi


class CourseHistoryActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()
        setContent { CourseHistoryScreen(getUser(this)!!) }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun CourseHistoryScreen(user: User) {
    val context = LocalContext.current

    val studentId = (context as Activity).intent.getStringExtra(IntentKey.STUDENT_ID)
        //sugar syntax for throw exception if null.
        ?: throw IllegalStateException("Student Id is required")

    var currentCourses by remember { mutableStateOf(listOf<CourseHistoryDto>())}
    var completedCourses by remember { mutableStateOf(listOf<CourseHistoryDto>())}

    val handleSet = {
        getCourseHistory(studentId) { res, isSuccess ->
            when (isSuccess) {
                true -> {
                    currentCourses = res.data!!.currentList
                    completedCourses = res.data!!.completedList
                }
                false -> Log.e("Get_COURSE_HISTORY", res.message)
            }
        }
    }

    handleSet()

    AppContainer {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp),
            verticalArrangement = Arrangement.SpaceBetween
        ) {
            Column {
                AppTitle("Current Courses")
                AppTable(
                    listOf("Course Id", "Course Name", "Credits"),
                    currentCourses.size
                ) { rowIndex ->
                    val course = currentCourses[rowIndex]
                    AppTableCell { AppText(course.courseId) }
                    AppTableCell { AppText(course.courseName) }
                    AppTableCell { AppText(course.credits) }
                }
                AppTitle("Completed Courses")
                AppTable(
                    listOf("Course Id", "Course Name", "Credits", "Grade"),
                    completedCourses.size
                ) { rowIndex ->
                    val course = completedCourses[rowIndex]
                    AppTableCell { AppText(course.courseId) }
                    AppTableCell { AppText(course.courseName) }
                    AppTableCell { AppText(course.credits) }
                    AppTableCell { AppText(course.grade ?: "-") }//If grade = null, use - instead. Required because
                }                                                //passing String? to function that requires String
            }
        }
            AppButton("Back") {
                startActivity(context, StudentActivity::class) {
                    putExtra(IntentKey.STUDENT_ID, user.id)
                }
            }
        }
    }


