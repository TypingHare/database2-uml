package edu.uml.db2

import android.app.Activity
import android.os.Bundle
import android.os.PersistableBundle
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
import edu.uml.db2.api.getInstructor
import edu.uml.db2.common.InstructorDto
import edu.uml.db2.common.IntentKey
import edu.uml.db2.common.User
import edu.uml.db2.common.getUser
import edu.uml.db2.common.removeUser
import edu.uml.db2.common.startActivity
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppTitle
import kotlinx.serialization.InternalSerializationApi

/**
 * Instructor course history
 *
 * @author Alexis Marx
 */
class InstructorHistoryActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?, persistentState: PersistableBundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()

        setContent { InstructorHistoryScreen(getUser(this)!!) }//todo: add screen function
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun InstructorHistoryScreen(user: User) {
    val context = LocalContext.current

    val instructorId = (context as Activity).intent.getStringExtra(IntentKey.INSTRUCTOR_ID)

    AppContainer {

    }
}