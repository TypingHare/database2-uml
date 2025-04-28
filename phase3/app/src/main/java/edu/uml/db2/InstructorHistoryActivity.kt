package edu.uml.db2

import android.app.Activity
import android.os.Bundle
import android.util.Log
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.heightIn
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.TextStyle
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import edu.uml.db2.api.getInstructorSections
import edu.uml.db2.common.InstructorSectionDto
import edu.uml.db2.common.IntentKey
import edu.uml.db2.common.finishActivity
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppTable
import edu.uml.db2.composable.AppTableCell
import edu.uml.db2.composable.AppText
import edu.uml.db2.composable.AppTopNavBar
import kotlinx.serialization.InternalSerializationApi

/**
 * Instructor course history
 *
 * @author Alexis Marx
 */
class InstructorHistoryActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()

        setContent { InstructorHistoryScreen() }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun InstructorHistoryScreen() {
    val context = LocalContext.current

    val instructorId = (context as Activity).intent.getStringExtra(IntentKey.INSTRUCTOR_ID) //?: ""
        ?: throw IllegalStateException("Instructor Id is required")
    var courseList by remember { mutableStateOf(emptyList<InstructorSectionDto>()) }

    LaunchedEffect(Unit) {
        getInstructorSections(instructorId) { res, isSuccess ->
            if (isSuccess) {
                courseList = res.data?.instructorSections ?: emptyList()
            } else {
                Log.e("COURSE_LIST_ERROR", "Failed to get course list: ${res.message}")
            }
        }
    }

    Column {
        AppTopNavBar("Course Records") { finishActivity(context) }
        AppContainer {
            LazyColumn {
                items(courseList.size) { sectionIndex ->
                    val section = courseList[sectionIndex]
                    Column {
                        Text(
                            modifier = Modifier.padding(top = 8.dp, bottom = 8.dp),
                            style = TextStyle(fontSize = 24.sp, fontWeight = FontWeight.Bold),
                            text = section.courseId + " " + section.sectionId
                        )

                        section.sections.forEach { instance ->
                            Text(
                                modifier = Modifier.padding(top = 16.dp, bottom = 8.dp),
                                style = TextStyle(fontSize = 18.sp),
                                text = instance.semester + " " + instance.year
                            )

                            Column(modifier = Modifier.heightIn(200.dp)) {
                                AppTable(
                                    listOf("Student ID", "Name", "Grade"),
                                    instance.students.size,
                                    scrollable = false
                                ) { rowIndex ->
                                    val student = instance.students[rowIndex]
                                    AppTableCell { AppText(student.studentId) }
                                    AppTableCell { AppText(student.name) }
                                    AppTableCell {
                                        AppText(student.grade ?: "-")
                                    }
                                }
                            }
                        }
                    }
                }
            }

        }
    }

//    Scaffold(
//        topBar = {
//            AppTopNavBar("Course Records") { finishActivity(context) }
//        }
//    ) { innerPadding ->
//        LazyColumn(
//            contentPadding = innerPadding,
//            modifier = Modifier.heightIn(500.dp)
//        ) {
//            items(courseList) { section ->
//                AppContainer {
//                    Column(
//                        modifier = Modifier
//                            .fillMaxWidth()
//                ) {
//                        Text(
//                            modifier = Modifier.padding(top = 8.dp, bottom = 8.dp),
//                            style = TextStyle(fontSize = 24.sp, fontWeight = FontWeight.Bold),
//                            text = section.courseId + " " + section.sectionId
//                        )
//
//                        section.sections.forEach { instance ->
//                            Text(
//                                modifier = Modifier.padding(top = 16.dp, bottom = 8.dp),
//                                style = TextStyle(fontSize = 18.sp),
//                                text = instance.semester + " " + instance.year
//                            )
//
//                            AppTable(
//                                listOf("Student ID", "Name", "Grade"), instance.students.size
//                            ) { rowIndex ->
//                                val student = instance.students[rowIndex]
//                                AppTableCell { AppText(student.studentId) }
//                                AppTableCell { AppText(student.name) }
//                                AppTableCell {
//                                    AppText(student.grade ?: "-")
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//
//        }
//    }
}
