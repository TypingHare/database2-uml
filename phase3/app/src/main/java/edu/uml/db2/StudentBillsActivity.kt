package edu.uml.db2

import android.os.Bundle
import android.util.Log
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.platform.LocalContext
import edu.uml.db2.api.getStudentAllBills
import edu.uml.db2.common.BillDto
import edu.uml.db2.common.BillStatus
import edu.uml.db2.common.IntentKey
import edu.uml.db2.common.finishActivity
import edu.uml.db2.common.startActivity
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppTable
import edu.uml.db2.composable.AppTableCell
import edu.uml.db2.composable.AppText
import edu.uml.db2.composable.AppTopNavBar
import kotlinx.serialization.InternalSerializationApi

class StudentBillsActivity : ComponentActivity() {
    var studentId = ""

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()

        studentId = intent.getStringExtra(IntentKey.STUDENT_ID)!!
        setContent { StudentBillsScreen(studentId, System.currentTimeMillis()) }
    }

    override fun onResume() {
        super.onResume()

        Log.e("STUDENT_BILLS_ACTIVITY", "onResume")
        setContent { StudentBillsScreen(studentId, System.currentTimeMillis()) }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun StudentBillsScreen(studentId: String, refreshKey: Long) {
    val context = LocalContext.current

    var bills by remember { mutableStateOf(listOf<BillDto>()) }

    LaunchedEffect(refreshKey) {
        getStudentAllBills(studentId) { res, isSuccess ->
            when (isSuccess) {
                true -> bills = res.data!!.list
                false -> Log.e("Get_STUDENT_ALL_BILLS", res.message)
            }
        }
    }

    val handleRowClick: (rowIndex: Int) -> Unit = {
        startActivity(context, BillPaymentActivity::class) {
            val (_, semester, year, status) = bills[it]
            putExtra(IntentKey.STUDENT_ID, studentId)
            putExtra(IntentKey.SEMESTER, semester)
            putExtra(IntentKey.YEAR, year)
            putExtra(IntentKey.BILL_STATUS, status)
        }
    }

    AppTopNavBar("My Bills") { finishActivity(context) }

    AppContainer {
        AppTable(listOf("Semester", "Year", "Status"), bills.size, handleRowClick) { rowIndex ->
            val (_, semester, year, status) = bills[rowIndex]
            AppTableCell { AppText(semester) }
            AppTableCell { AppText(year) }
            AppTableCell { BillStatusText(status) }
        }
    }
}

@Composable
fun BillStatusText(status: String) {
    val color = if (status == BillStatus.PAID) Color(0xFF06D6A0) else Color(0xFFEF476F)
    Text(text = status, color = color)
}