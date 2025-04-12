package edu.uml.db2.composable

import androidx.compose.foundation.layout.ColumnScope
import androidx.compose.runtime.Composable
import androidx.compose.ui.unit.Dp
import androidx.compose.ui.unit.dp

/**
 * A combination of `AppCenterColumn` and `AppSpacedColumn` to reduce nests.
 * @see AppCenterColumn
 * @see AppSpacedColumn
 */
@Composable
fun AppContainer(space: Dp = 16.dp, content: @Composable ColumnScope.() -> Unit) =
    AppCenterColumn {
        AppSpacedColumn(space = space, content = content)
    }