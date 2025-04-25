package edu.uml.db2

import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.runtime.Composable
import androidx.compose.ui.platform.LocalContext
import edu.uml.db2.common.removeUser
import edu.uml.db2.common.startActivity
import edu.uml.db2.composable.AppButton
import edu.uml.db2.composable.AppContainer
import edu.uml.db2.composable.AppTopNavBar

/**
 * Admin dashboard.
 *
 * @author James Chen
 */
class AdminActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        enableEdgeToEdge()
        setContent { AdminScreen() }
    }
}

@Composable
fun AdminScreen() {
    val context = LocalContext.current

    AppTopNavBar("Admin Dashboard", false)

    AppContainer {
        AppButton("View Bills") { startActivity(context, BillsActivity::class) }
        AppButton("Sign Out") {
            removeUser(context)
            startActivity(context, LoginActivity::class, finish = true)
        }
    }
}