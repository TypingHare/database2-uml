package edu.uml.db2.composable

import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.material3.Button
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier

/**
 * This composable function represents a full width button with a specified text.
 *
 * @author James Chen
 */
@Composable
fun AppButton(
    text: String, isFullWidth: Boolean = true, enabled: Boolean = true, onClick: () -> Unit
) {
    Button(
        onClick = onClick,
        enabled = enabled,
        modifier = if (isFullWidth) Modifier.fillMaxWidth() else Modifier
    ) { Text(text) }
}