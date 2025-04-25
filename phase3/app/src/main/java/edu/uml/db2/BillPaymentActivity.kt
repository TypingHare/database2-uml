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
import edu.uml.db2.api.getScholarship
import edu.uml.db2.api.getStudent
import edu.uml.db2.api.getStudentSectionBySemester
import edu.uml.db2.api.payBill
import edu.uml.db2.common.IntentKey
import edu.uml.db2.common.SectionDto
import edu.uml.db2.common.finishActivity
import edu.uml.db2.common.startActivity
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppCard
import edu.uml.db2.composable.AppCardRow
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppTable
import edu.uml.db2.composable.AppTableCell
import edu.uml.db2.composable.AppText
import edu.uml.db2.composable.AppTitle
import kotlinx.serialization.InternalSerializationApi

class BillPaymentActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()

        val studentId = intent.getStringExtra(IntentKey.STUDENT_ID)!!
        val semester = intent.getStringExtra(IntentKey.SEMESTER)!!
        val year = intent.getStringExtra(IntentKey.YEAR)!!
        val status = intent.getStringExtra(IntentKey.BILL_STATUS)!!
        setContent { BillPaymentScreen(studentId, semester, year, status) }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun BillPaymentScreen(
    studentId: String, semester: String, year: String, status: String
) {
    val context = LocalContext.current

    var sections by remember { mutableStateOf(listOf<SectionDto>()) }
    var scholarship by remember { mutableStateOf(0) }
    var studentName by remember { mutableStateOf("") }

    getStudentSectionBySemester(studentId, semester, year) { res, isSuccess ->
        when (isSuccess) {
            true -> sections = res.data!!.sections
            false -> Log.e("GET_STUDENT_SECTION_BY_SEMESTER", res.message)
        }
    }

    getScholarship(studentId, semester, year) { res, isSuccess ->
        when (isSuccess) {
            true -> scholarship = res.data!!.scholarship
            false -> Log.e("GET_SCHOLARSHIP", res.message)
        }
    }

    getStudent(studentId) { res, isSuccess ->
        when (isSuccess) {
            true -> studentName = res.data!!.name
            false -> Log.e("GET_STUDENT", res.message)
        }
    }

    val totalCredits = sections.sumOf { it.credits.toInt() }
    val totalTuition = getTuition(totalCredits)
    val amount = totalTuition - scholarship

    AppContainer {
        AppTitle("Bill Payment")
        AppCard {
            AppCardRow("Student ID", studentId)
            AppCardRow("Semester", semester)
            AppCardRow("Year", year)
        }
        AppTable(
            listOf("Course ID", "Course Name", "Credits", "Tuition"), sections.size
        ) { rowIndex ->
            val (_, courseId, _, _, _, _, courseName, credits) = sections[rowIndex]
            AppTableCell { AppText(courseId) }
            AppTableCell { AppText(courseName) }
            AppTableCell { AppText(credits) }
            AppTableCell { AppText('$' + getTuition(credits.toInt()).toString()) }
        }
        AppCard {
            AppCardRow("Total Tuition", "$$totalTuition")
            AppCardRow("Scholarship", "-$$scholarship")
            AppCardRow("Amount", "$$amount")
            AppCardRow("Status") { BillStatusText(status) }
        }
        AppButton("Pay") {
            payBill(studentId, semester, year) { res, isSuccess ->
                when (isSuccess) {
                    true -> startActivity(context, PaymentSuccessActivity::class, finish = true) {
                        putExtra(IntentKey.STUDENT_NAME, studentName)
                        putExtra(IntentKey.SEMESTER, semester)
                        putExtra(IntentKey.YEAR, year)
                        putExtra(IntentKey.AMOUNT, amount.toString())
                    }

                    false -> Log.e("PAY_BILL", res.message)
                }
            }
        }
        AppButton("Cancel") { finishActivity(context) }
    }
}

fun getTuition(credits: Int): Int = credits * 800