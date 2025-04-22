package edu.uml.db2

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.runtime.Composable
import androidx.compose.ui.platform.LocalContext
import edu.uml.db2.common.User
import edu.uml.db2.common.getUser
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppTitle
import kotlinx.serialization.InternalSerializationApi


class CourseHistoryActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()
        setContent { CourseHistoryScreen(getUser(this)!!) }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun CourseHistoryScreen(user: User) {
    val context = LocalContext.current

    AppContainer {


        AppTitle("Current Courses")

        AppTitle("Completed Courses")
    }
}
