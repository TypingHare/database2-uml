package edu.uml.db2.composable

import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.sp

/**
 * This composable function represents a title of a specified text.
 *
 * @author James Chen
 */
@Composable
fun AppTitle(title: String) = Text(
    text = title,
    fontSize = 32.sp,
    fontWeight = FontWeight.Bold,
)