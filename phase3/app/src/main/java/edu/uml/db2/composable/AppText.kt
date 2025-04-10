package edu.uml.db2.composable

import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Color

@Composable
fun AppText(message: String) = Text(
    modifier = Modifier.fillMaxWidth(),
    text = message,
)

@Composable
fun AppErrorText(message: String) = Text(
    modifier = Modifier.fillMaxWidth(),
    color = Color.Red,
    text = message,
)