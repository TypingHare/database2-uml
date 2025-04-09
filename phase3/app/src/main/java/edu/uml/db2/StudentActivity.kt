package edu.uml.db2

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.runtime.Composable
import kotlinx.serialization.InternalSerializationApi

/**
 * Student dashboard activity.
 */
class StudentActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContent {
            StudentScreen()
        }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun StudentScreen() {

}
