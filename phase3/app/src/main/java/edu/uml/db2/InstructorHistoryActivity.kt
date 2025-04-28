package edu.uml.db2

import android.app.Activity
import android.os.Bundle
import android.os.PersistableBundle
import android.util.Log
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.PaddingValues
import androidx.compose.foundation.layout.fillMaxHeight
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.verticalScroll
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.TextStyle
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import edu.uml.db2.api.getInstructorSections
import edu.uml.db2.api.register
import edu.uml.db2.common.InstructorSectionDto
import edu.uml.db2.common.InstructorSectionListDto
import edu.uml.db2.common.IntentKey
import edu.uml.db2.common.User
import edu.uml.db2.common.finishActivity
import edu.uml.db2.common.getUser
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppSpacedColumn
import edu.uml.db2.composable.AppTable
import edu.uml.db2.composable.AppTableCell
import edu.uml.db2.composable.AppText
import edu.uml.db2.composable.AppTitle
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

        setContent { InstructorHistoryScreen(getUser(this)!!) }
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
        getInstructorSections(instructorId) { res, isSuccess ->
            if (isSuccess) {
                courseList = res.data?.instructorSections ?: emptyList()
            } else {
                Log.e("COURSE_LIST_ERROR", "Failed to get course list: ${res.message}")
            }
        }

    }

    Scaffold(
        topBar = {
            AppTopNavBar("Course Records") { finishActivity(context) }
        }
    ) { innerPadding ->
        Column(modifier = Modifier.padding(innerPadding)) {
            AppContainer {
                Column(
                    modifier = Modifier
                        .fillMaxSize()
                ) {
                    courseList.forEach { section ->
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

                            AppTable(
                                listOf("Student ID", "Name", "Grade"), instance.students.size
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
