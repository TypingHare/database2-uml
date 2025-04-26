package edu.uml.db2.composable

import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material3.CenterAlignedTopAppBar
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.Icon
import androidx.compose.material3.IconButton
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.material3.TopAppBarDefaults
import androidx.compose.runtime.Composable
import androidx.compose.ui.graphics.Color

/**
 * A top navigation bar. In a modern mobile application, there are two different navigation bars on
 * the top and on the bottom of the screen. The top navigation bar usually includes the title of the
 * page and a back button, which allows users to go to the previous page (i.e., close the current
 * page).
 *
 * @param title The title string displayed in the top navigation bar.
 * @param displayBackArrow Whether to display the back arrow icon.
 * @param onBackClick A callback function that is fired when the back arrow icon is tapped.
 * @author James Chen
 */
@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun AppTopNavBar(
    title: String,
    displayBackArrow: Boolean = true,
    onBackClick: (() -> Unit)? = null
) {
    CenterAlignedTopAppBar(
        title = {
            Text(text = title, color = Color.White)
        }, navigationIcon = {
            IconButton(onClick = onBackClick ?: {}) {
                if (displayBackArrow) {
                    Icon(
                        imageVector = Icons.AutoMirrored.Filled.ArrowBack,
                        contentDescription = "Back",
                        tint = Color.White
                    )
                }
            }
        }, colors = TopAppBarDefaults.topAppBarColors(
            containerColor = MaterialTheme.colorScheme.primary
        )
    )
}