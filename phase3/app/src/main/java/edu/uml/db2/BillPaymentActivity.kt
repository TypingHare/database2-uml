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
        setContent { BillPaymentScreen(studentId, semester, year) }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun BillPaymentScreen(
    studentId: String, semester: String, year: String
) {
    val context = LocalContext.current

    var sections by remember { mutableStateOf(listOf<SectionDto>()) }
    var scholarship by remember { mutableStateOf(0) }

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

    val totalCredits = sections.sumOf { it.credits.toInt() }
    val totalTuition = getTuition(totalCredits)

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
            AppCardRow("Total Tuition", totalTuition.toString())
            AppCardRow("Scholarship", "-$$scholarship")
            AppCardRow("Amount", (totalTuition - scholarship).toString())
            AppCardRow("Status", "Paid")
        }
        AppButton("Pay") {
            payBill(studentId, semester, year) { res, isSuccess ->
                when (isSuccess) {
                    // TODO: This should be PaymentSuccessActivity
                    true -> startActivity(context, BillPaymentActivity::class)
                    false -> Log.e("PAY_BILL", res.message)
                }
            }
        }
        AppButton("Cancel") { finishActivity(context) }
    }
}

fun getTuition(credits: Int): Int = credits * 800