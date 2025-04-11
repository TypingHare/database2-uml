package edu.uml.db2.composable

import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color

/**
 * This composable function represents a standard full width text.
 */
@Composable
fun AppText(message: String) = Text(
    modifier = Modifier.fillMaxWidth(),
    text = message,
)

/**
 * This composable function represents a standard full width error text. The color of the text is
 * red.
 */
@Composable
fun AppErrorText(message: String) = Text(
    modifier = Modifier.fillMaxWidth(),
    color = Color.Red,
    text = message,
)