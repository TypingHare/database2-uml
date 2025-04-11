package edu.uml.db2.composable

import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.ColumnScope
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.padding
import androidx.compose.runtime.Composable
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.unit.dp

/**
 * This composable function represents a column that has 24.dp of padding, and align the content
 * center both vertically and horizontally.
 */
@Composable
fun AppCenterColumn(content: @Composable ColumnScope.() -> Unit) = Column(
    modifier = Modifier
        .fillMaxSize()
        .padding(24.dp),
    verticalArrangement = Arrangement.Center,
    horizontalAlignment = Alignment.CenterHorizontally,
    content = content
)