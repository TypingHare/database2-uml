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
import edu.uml.db2.api.getInstructor
import edu.uml.db2.common.InstructorDto
import edu.uml.db2.common.User
import edu.uml.db2.common.getUser
import edu.uml.db2.common.removeUser
import edu.uml.db2.common.startActivity
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppCard
import edu.uml.db2.composable.AppCardRow
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppTitle
import kotlinx.serialization.InternalSerializationApi

/**
 * Instructor dashboard.
 *
 * @author James Chen
 */
class InstructorActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()
        setContent { InstructorScreen(getUser(this)!!) }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun InstructorScreen(user: User) {
    val context = LocalContext.current

    var instructor by remember { mutableStateOf<InstructorDto?>(null) }

    getInstructor(user.id!!) { res, isSuccess ->
        when (isSuccess) {
            true -> instructor = res.data
            false -> Log.e("GET_INSTRUCTOR", res.message)
        }
    }

    AppContainer {
        AppTitle("Instructor Dashboard")
        instructor?.let { InstructorCard(it) }
        AppButton("Sign Out") {
            removeUser(context)
            startActivity(context, LoginActivity::class, finish = true)
        }
    }
}

@OptIn(InternalSerializationApi::class)
@Composable
fun InstructorCard(instructor: InstructorDto) {
    AppCard {
        AppCardRow("Instructor ID", instructor.instructorId)
        AppCardRow("Name", instructor.instructorName)
        AppCardRow("Email", instructor.email)
        AppCardRow("Title", instructor.title)
        AppCardRow("Department", instructor.deptName)
    }
}