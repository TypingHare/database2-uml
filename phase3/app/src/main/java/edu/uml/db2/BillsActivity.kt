package edu.uml.db2

import android.annotation.SuppressLint
import android.os.Bundle
import android.util.Log
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.layout.BoxWithConstraints
import androidx.compose.material3.HorizontalDivider
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.platform.LocalContext
import edu.uml.db2.api.getBills
import edu.uml.db2.common.IntentKey
import edu.uml.db2.common.StudentBillDto
import edu.uml.db2.common.finishActivity
import edu.uml.db2.common.startActivity
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppSpacedColumn
import edu.uml.db2.composable.AppSpacedRow
import edu.uml.db2.composable.AppTable
import edu.uml.db2.composable.AppTableCell
import edu.uml.db2.composable.AppText
import edu.uml.db2.composable.AppTextField
import kotlinx.serialization.InternalSerializationApi

class BillsActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()
        setContent { BillsScreen() }
    }

    override fun onResume() {
        super.onResume()

        setContent { BillsScreen() }
    }
}

@OptIn(InternalSerializationApi::class)
@SuppressLint("UnusedBoxWithConstraintsScope")
@Composable
fun BillsScreen() {
    val context = LocalContext.current

    var year by remember { mutableStateOf("2025") }
    var semester by remember { mutableStateOf("Fall") }
    var studentBillList by remember { mutableStateOf(listOf<StudentBillDto>()) }

    val handleSet = {
        getBills(semester, year) { res, isSuccess ->
            when (isSuccess) {
                true -> studentBillList = res.data!!.list
                false -> Log.e("GET_BILLS", res.message)
            }
        }
    }

    val handleRowClick: (Int) -> Unit = { rowIndex ->
        val (studentId) = studentBillList[rowIndex]
        startActivity(context, OperateStudentBillActivity::class) {
            putExtra(IntentKey.STUDENT_ID, studentId)
            putExtra(IntentKey.SEMESTER, semester)
            putExtra(IntentKey.YEAR, year)
        }
    }

    handleSet()

    AppContainer {
        BoxWithConstraints {
            if (constraints.maxWidth < 1000) {
                AppSpacedColumn {
                    AppTextField("Semester", semester) { semester = it }
                    AppTextField("Year", year) { year = it }
                    AppButton("Set", onClick = handleSet)
                }
            } else {
                AppSpacedRow {
                    AppTextField("Semester", semester, isFullWidth = false) { semester = it }
                    AppTextField("Year", year, isFullWidth = false) { year = it }
                    AppButton("Set", onClick = handleSet)
                }
            }
        }

        AppButton("Back") { finishActivity(context) }
        HorizontalDivider()
        AppTable(
            listOf("Student ID", "Student Name", "Status", "Scholarship"),
            studentBillList.size,
            rowOnClick = handleRowClick
        ) { rowIndex ->
            val (studentId, name, _, _, _, _, status, scholarship) = studentBillList[rowIndex]
            AppTableCell { AppText(studentId) }
            AppTableCell { AppText(name) }
            AppTableCell { AppText(status) }
            AppTableCell { AppText("$$scholarship") }
        }
    }
}