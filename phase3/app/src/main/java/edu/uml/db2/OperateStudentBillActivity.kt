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
import edu.uml.db2.api.createBill
import edu.uml.db2.api.createScholarship
import edu.uml.db2.api.getStudentBill
import edu.uml.db2.common.BillStatus
import edu.uml.db2.common.IntentKey
import edu.uml.db2.common.StudentBillDto
import edu.uml.db2.common.finishActivity
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppCard
import edu.uml.db2.composable.AppCardRow
import edu.uml.db2.composable.AppContainer
import kotlinx.serialization.InternalSerializationApi

class OperateStudentBillActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()

        val studentId = intent.getStringExtra(IntentKey.STUDENT_ID)!!
        val semester = intent.getStringExtra(IntentKey.SEMESTER)!!
        val year = intent.getStringExtra(IntentKey.YEAR)!!
        setContent {
            OperateStudentBillScreen(studentId, semester, year)
        }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun OperateStudentBillScreen(studentId: String, semester: String, year: String) {
    val context = LocalContext.current

    var studentBill by remember { mutableStateOf<StudentBillDto?>(null) }

    getStudentBill(studentId, semester, year) { res, isSuccess ->
        when (isSuccess) {
            true -> studentBill = res.data!!
            false -> Log.e("STUDENT_BILL", res.message)
        }
    }

    AppContainer {
        studentBill?.let {
            AppCard {
                AppCardRow("studentId", studentId)
                AppCardRow("name", it.name)
                AppCardRow("email", it.email)
                AppCardRow("semester", it.semester)
                AppCardRow("year", it.year)
                AppCardRow("status", it.status)
                AppCardRow("scholarship", "$" + it.scholarship.toString())
            }
        }

        AppButton("Create Bill") {
            createBill(studentId, semester, year) { res, isSuccess ->
                when (isSuccess) {
                    true -> studentBill = studentBill!!.copy(status = BillStatus.UNPAID)
                    false -> Log.e("CREATE_BILL", res.message)
                }
            }
        }
        AppButton("Reward") {
            createScholarship(studentId, semester, year) { res, isSuccess ->
                when (isSuccess) {
                    true -> studentBill = res.data!!
                    false -> Log.e("CREATE_SCHOLARSHIP", res.message)
                }
            }
        }
        AppButton("Back") { finishActivity(context) }
    }
}