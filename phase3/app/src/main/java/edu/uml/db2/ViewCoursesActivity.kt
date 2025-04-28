package edu.uml.db2

import android.app.Activity
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import android.util.Log
import android.widget.Toast
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.activity.ComponentActivity
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.setValue
import androidx.compose.ui.platform.LocalContext
import edu.uml.db2.api.getCourseList
import edu.uml.db2.api.getStudent
import edu.uml.db2.common.CourseDto
import edu.uml.db2.common.CourseListDto
import edu.uml.db2.common.IntentKey
import edu.uml.db2.common.StudentBillDto
import edu.uml.db2.common.StudentDto
import edu.uml.db2.common.StudentType
import edu.uml.db2.common.User
import edu.uml.db2.common.finishActivity
import edu.uml.db2.common.getUser
import edu.uml.db2.common.removeUser
import edu.uml.db2.common.startActivity
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppCard
import edu.uml.db2.composable.AppCardRow
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppTable
import edu.uml.db2.composable.AppTableCell
import edu.uml.db2.composable.AppText
import edu.uml.db2.composable.AppTitle
import kotlinx.coroutines.delay
import kotlinx.serialization.InternalSerializationApi
import kotlin.collections.get
import kotlin.system.exitProcess
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.ui.Alignment
import androidx.compose.ui.unit.dp
import edu.uml.db2.api.login
import edu.uml.db2.api.register
import edu.uml.db2.common.LoginDto
import edu.uml.db2.common.RegisterDto
import edu.uml.db2.common.UserType
import edu.uml.db2.common.saveUser
import edu.uml.db2.composable.AppErrorText
import edu.uml.db2.composable.AppSpacedRow
import edu.uml.db2.composable.AppTopNavBar

/**
 * View courses
 *
 * @author Alexis Marx
 */
class ViewCoursesActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()
        setContent { CoursesScreen() }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun CoursesScreen() {
    val context = LocalContext.current

    val studentId = (context as Activity).intent.getStringExtra(IntentKey.STUDENT_ID)
        ?: throw IllegalStateException("Student Id is required")
    var courseList by remember { mutableStateOf(emptyList<CourseDto>()) }

    var successStr by remember { mutableStateOf<String>("") }
    var errorStr by remember { mutableStateOf<String>("") }

    var regSuccess by remember { mutableStateOf(false) }
    var regError by remember { mutableStateOf(false) }

    //Log.d("BREADCRUMB", "CoursesScreen started")
    LaunchedEffect(Unit) {
        //Log.d("BREADCRUMB", "call reached")
        getCourseList() { res, isSuccess ->
//        when (isSuccess) {
//            //true -> courseList = res.data?.list ?: emptyList()
//            true -> courseList = res.data!!.list
//            false -> Log.e("GET_COURSES", res.message)
//        }
            //Log.d("BREADCRUMB", "getCourseList callback reached")
            if (isSuccess) {
                //Log.d("COURSE_LIST_SUCCESS", "Parsed ${res.data?.list?.size} courses")
                //res.data?.list?.forEach {
                    //Log.d("COURSE_ITEM", it.toString())  // or log specific fields
                //}
                courseList = res.data?.list ?: emptyList()
            } else {
                Log.e("COURSE_LIST_ERROR", "Failed to get course list: ${res.message}")
            }
        }

    }

    val handleRegisterSuccess: (RegisterDto) -> Unit = { registerDto ->
        regSuccess = true
        successStr = "You have successfully registered for ${registerDto.courseId} ${registerDto.sectionId}!"
    }

    val handleRegisterError: (String) -> Unit = { message ->
        regError = true
        errorStr = message
    }

    AppTopNavBar("Fall 2025 Courses") { finishActivity(context) }
    AppContainer {
        if (courseList.isEmpty()) {
            AppText("Loading or no courses found.")
        }

        if (regSuccess) {
            AppCard {
                AppCardRow("Notice: ", successStr)
            }
        }

        if (regError) {
            AppCard {
                AppCardRow("Error: ", errorStr)
            }
        }

        LazyColumn(
            contentPadding = PaddingValues(horizontal = 16.dp),
            verticalArrangement = Arrangement.spacedBy(12.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {


            items(courseList) { course ->
                CourseCard(course)
                AppButton("Attempt Register", isFullWidth = false) {
                    register(studentId, course.courseId, course.sectionId) { res, isSuccess ->
                        when (isSuccess) {
                            true -> handleRegisterSuccess(res.data!!)
                            false -> handleRegisterError(res.message)
                        }
                    }
                }
            }
        }

    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun CourseCard(course: CourseDto) {
    val scheduleStr = course.day + " " + course.startTime + "-" + course.endTime
    val roomStr = course.building + " " + course.roomNumber
    AppCard {
        AppCardRow("Course ID", course.courseId)
        AppCardRow("Section ID", course.sectionId)
        AppCardRow("Instructor", course.instructorName)
        AppCardRow("Schedule", scheduleStr)
        AppCardRow("Classroom", roomStr)
    }
}