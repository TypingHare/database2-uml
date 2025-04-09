package edu.uml.db2.composable

import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.sp

@Composable
fun AppTitle(title: String) = Text(
    text = title,
    fontSize = 32.sp,
    fontWeight = FontWeight.Bold,
)