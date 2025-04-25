package edu.uml.db2

import android.os.Bundle
import android.os.Handler
import android.os.Looper
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.height
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import edu.uml.db2.common.IntentKey
import edu.uml.db2.composable.AppContainer

class PaymentSuccessActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()

        val studentName = intent.getStringExtra(IntentKey.STUDENT_NAME)!!
        val semester = intent.getStringExtra(IntentKey.SEMESTER)!!
        val year = intent.getStringExtra(IntentKey.YEAR)!!
        val amount = intent.getStringExtra(IntentKey.AMOUNT)!!
        setContent { PaymentSuccessScreen(studentName, semester, year, amount) }

        Handler(Looper.getMainLooper()).postDelayed({
            finish()
        }, 5000)
    }
}

@Composable
fun PaymentSuccessScreen(
    studentName: String, semester: String, year: String, amount: String
) {
    AppContainer {
        Text(
            text = "Thank you, $studentName.",
            fontSize = 32.sp,
            fontWeight = FontWeight.Bold,
            color = Color(0xFF06D6A0)
        )
        Spacer(modifier = Modifier.height(8.dp))
        Text(
            text = "Your payment of $$amount for $semester $year tuition was successful!",
            fontSize = 24.sp,
            color = Color(0xFF06D6A0)
        )
        Spacer(modifier = Modifier.height(16.dp))
        Text(
            text = "The page will be closed in 5 seconds...",
        )
    }
}