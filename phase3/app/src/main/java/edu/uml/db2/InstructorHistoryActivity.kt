package edu.uml.db2

import android.app.Activity
import android.os.Bundle
import android.os.PersistableBundle
import android.util.Log
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.unit.dp
import edu.uml.db2.api.getInstructorSections
import edu.uml.db2.api.register
import edu.uml.db2.common.InstructorSectionDto
import edu.uml.db2.common.InstructorSectionListDto
import edu.uml.db2.common.IntentKey
import edu.uml.db2.common.User
import edu.uml.db2.common.getUser
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppTable
import edu.uml.db2.composable.AppTableCell
import edu.uml.db2.composable.AppText
import edu.uml.db2.composable.AppTitle
import kotlinx.serialization.InternalSerializationApi

/**
 * Instructor course history
 *
 * @author Alexis Marx
 */
class InstructorHistoryActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?, persistentState: PersistableBundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()

        setContent { InstructorHistoryScreen(getUser(this)!!) }//todo: add screen function
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun InstructorHistoryScreen(user: User) {
    val context = LocalContext.current

    val instructorId = (context as Activity).intent.getStringExtra(IntentKey.INSTRUCTOR_ID) //?: ""
        ?: throw IllegalStateException("Instructor Id is required")
    var courseList by remember { mutableStateOf(emptyList<InstructorSectionDto>()) }

    LaunchedEffect(Unit) {
        Log.d("BREADCRUMB", "call reached")
        getInstructorSections(instructorId) { res, isSuccess ->
//        when (isSuccess) {
//            //true -> courseList = res.data?.list ?: emptyList()
//            true -> courseList = res.data!!.list
//            false -> Log.e("GET_COURSES", res.message)
//        }
            Log.d("BREADCRUMB", "getCourseList callback reached")
            if (isSuccess) {
                Log.d("COURSE_LIST_SUCCESS", "Parsed ${res.data?.sections?.size} courses")
                res.data?.sections?.forEach {
                Log.d("COURSE_ITEM", it.toString())  // or log specific fields
                }
                courseList = res.data!!.sections
            } else {
                Log.e("COURSE_LIST_ERROR", "Failed to get course list: ${res.message}")
            }
        }

    }

    AppContainer {
        AppTitle("Course Records")

        LazyColumn(
            contentPadding = PaddingValues(horizontal = 16.dp),
            verticalArrangement = Arrangement.spacedBy(12.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            items(courseList) { section ->
                AppTitle()
                AppTable(
                    listOf("Name", "Grade"), section.size
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