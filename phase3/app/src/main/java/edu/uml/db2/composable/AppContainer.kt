package edu.uml.db2.composable

import androidx.compose.foundation.layout.ColumnScope
import androidx.compose.runtime.Composable

@Composable
fun AppContainer(content: @Composable ColumnScope.() -> Unit) =
    AppCenterColumn {
        AppSpacedColumn(content = content)
    }