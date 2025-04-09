package edu.uml.db2

import android.content.Intent
import android.os.Bundle
import androidx.activity.ComponentActivity
import androidx.activity.enableEdgeToEdge

// In Android, an activity is a single screen with a user interface in an Android app.
// Activity is a type of component. Other components include:
//     1. Service: Does background work
//     2. BroadcastReceiver – Listens for system/app-wide messages
//     3. ContentProvider – Shares data between apps
// In this project, we are only using activity, so activity and component are used interchangeably.
class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)

        // Configures the app to draw behind system bars (like the status bar or navigation bar)
        enableEdgeToEdge()

        // Starts the `LoginActivity`
        // An intent is a messaging object used to create another activity
        // Here we use an explicit intent to create a specific component
        startActivity(Intent(this, LoginActivity::class.java))

        // Close the current activity (MainActivity)
        // Therefore, when the user close the new activity, Android will not bring them into this
        // page; if we want the user to return to this page later on, remove this statement
        finish()
    }
}
