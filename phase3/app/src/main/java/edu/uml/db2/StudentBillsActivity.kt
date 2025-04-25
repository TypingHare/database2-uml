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
import edu.uml.db2.api.getStudentAllBills
import edu.uml.db2.common.BillDto
import edu.uml.db2.common.IntentKey
import edu.uml.db2.common.finishActivity
import edu.uml.db2.common.startActivity
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppTable
import edu.uml.db2.composable.AppTableCell
import edu.uml.db2.composable.AppText
import edu.uml.db2.composable.AppTitle
import kotlinx.serialization.InternalSerializationApi

class StudentBillsActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()
        setContent { StudentBillsScreen(intent.getStringExtra(IntentKey.STUDENT_ID)!!) }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun StudentBillsScreen(studentId: String) {
    val context = LocalContext.current

    var bills by remember { mutableStateOf(listOf<BillDto>()) }

    getStudentAllBills(studentId) { res, isSuccess ->
        when (isSuccess) {
            true -> bills = res.data!!.list
            false -> Log.e("Get_STUDENT_ALL_BILLS", res.message)
        }
    }

    val handleRowClick: (rowIndex: Int) -> Unit = {
        startActivity(context, BillPaymentActivity::class) {
            val (_, semester, year, _) = bills[it]
            putExtra(IntentKey.STUDENT_ID, studentId)
            putExtra(IntentKey.SEMESTER, semester)
            putExtra(IntentKey.YEAR, year)
        }
    }

    AppContainer {
        AppTitle("My Bills")
        AppTable(listOf("Semester", "Year", "Status"), bills.size, handleRowClick) { rowIndex ->
            val (_, semester, year, status) = bills[rowIndex]
            AppTableCell { AppText(semester) }
            AppTableCell { AppText(year) }
            AppTableCell { AppText(status) }
        }
        AppButton("Back") { finishActivity(context) }
    }
}
