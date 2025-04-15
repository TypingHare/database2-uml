package edu.uml.db2.composable

import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.ColumnScope
import androidx.compose.runtime.Composable
import androidx.compose.ui.unit.Dp
import androidx.compose.ui.unit.dp

/**
 * This composable function represents a column where there is a specified space between every two
 * composable inside the content.
 *
 * @author James Chen
 */
@Composable
fun AppSpacedColumn(space: Dp = 16.dp, content: @Composable ColumnScope.() -> Unit) =
    Column(verticalArrangement = Arrangement.spacedBy(space), content = content)