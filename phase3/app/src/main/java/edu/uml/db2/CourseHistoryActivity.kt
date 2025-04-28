package edu.uml.db2

import android.app.Activity
import android.os.Bundle
import android.util.Log
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Scaffold
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
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
import edu.uml.db2.common.finishActivity
import edu.uml.db2.common.getUser
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppTable
import edu.uml.db2.composable.AppTableCell
import edu.uml.db2.composable.AppText
import edu.uml.db2.composable.AppTitle
import edu.uml.db2.composable.AppTopNavBar
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

    var currentCourses by remember { mutableStateOf(listOf<CourseHistoryDto>()) }
    var completedCourses by remember { mutableStateOf(listOf<CourseHistoryDto>()) }

//    val handleSet = {
//        getCourseHistory(studentId) { res, isSuccess ->
//            when (isSuccess) {
//                true -> {
//                    currentCourses = res.data!!.currentList
//                    completedCourses = res.data.completedList
//                }
//
//                false -> Log.e("Get_COURSE_HISTORY", res.message)
//            }
//        }
//    }

    LaunchedEffect(studentId) {
        getCourseHistory(studentId) { res, isSuccess ->
            when (isSuccess) {
                true -> {
                    currentCourses = res.data!!.currentList
                    completedCourses = res.data.completedList
                }

                false -> Log.e("GET_COURSE_HISTORY", res.message)
            }
        }
    }


    Scaffold (
        topBar = {
            AppTopNavBar("Course History") { finishActivity(context) }
        }
    ) { innerPadding ->
        Column(modifier = Modifier.padding(innerPadding)) {
            AppContainer (
            ) {
                Column (
                    modifier = Modifier
                        .fillMaxSize()
                ) {
                    AppTitle("Current Courses")
                    Spacer(modifier = Modifier.height(16.dp))
                    if (currentCourses.isEmpty()) {
                        AppText("No courses registered")
                    } else {
                        AppTable(
                            listOf("Course Id", "Course Name", "Credits"),
                            currentCourses.size,
                        ) { rowIndex ->
                            val course = currentCourses[rowIndex]
                            AppTableCell { AppText(course.courseId) }
                            AppTableCell { AppText(course.courseName) }
                            AppTableCell { AppText(course.credits) }
                        }
                    }
                    Spacer(modifier = Modifier.height(32.dp))
                    AppTitle("Completed Courses")
                    Spacer(modifier = Modifier.height(16.dp))
                    if (completedCourses.isEmpty()) {
                        AppText("No completed courses")
                    } else {
                        AppTable(
                            listOf("Course Id", "Course Name", "Credits", "Grade"), completedCourses.size
                        ) { rowIndex ->
                            val course = completedCourses[rowIndex]
                            AppTableCell { AppText(course.courseId) }
                            AppTableCell { AppText(course.courseName) }
                            AppTableCell { AppText(course.credits) }
                            AppTableCell {
                                AppText(
                                    course.grade ?: "-"
                                )
                            }
                        }
                    }
                }
            }
        }
    }
}



