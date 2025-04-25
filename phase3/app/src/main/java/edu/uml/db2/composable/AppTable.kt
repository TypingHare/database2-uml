package edu.uml.db2.composable

import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.ColumnScope
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.RowScope
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.material3.HorizontalDivider
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.drawBehind
import androidx.compose.ui.geometry.Offset
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.text.style.TextAlign
import androidx.compose.ui.unit.dp

/**
 * @see <a>https://mui.com/material-ui/react-table/</a>
 */
@Composable
fun AppTable(
    header: List<String>,
    numRow: Int,
    modifier: Modifier = Modifier,//added this to try and fix an issue. should be kept for flexibility and future proofing?
    rowOnClick: ((rowIndex: Int) -> Unit)? = null,//had to explicitly call rowOnClick = handleRowClick in bill activity because kotlin uses positioning
    rowBuilder: @Composable RowScope.(rowIndex: Int) -> Unit,
) {
    Column {
        Row(
            modifier = Modifier
                .fillMaxWidth()
                .background(Color(0xFF333333))
                .padding(vertical = 12.dp, horizontal = 8.dp)
        ) {
            header.forEach { cell ->
                Text(
                    text = cell,
                    modifier = Modifier
                        .weight(1f)
                        .padding(horizontal = 4.dp),
                    style = MaterialTheme.typography.bodyMedium.copy(
                        fontWeight = FontWeight.Bold,
                        textAlign = TextAlign.Start,
                        color = Color.White
                    )
                )
            }
        }

        LazyColumn {
            items(numRow) { rowIndex ->
                val bgColor = if (rowIndex % 2 == 0) Color.White else Color(0xFFF5F5F5)
                val rowModifier = Modifier
                    .fillMaxWidth()
                    .background(bgColor)
                    .drawBehind {
                        drawLine(
                            color = Color.LightGray,
                            start = Offset(0f, 0f),
                            end = Offset(0f, size.height),
                            strokeWidth = 1.dp.toPx()
                        )
                        drawLine(
                            color = Color.LightGray,
                            start = Offset(size.width, 0f),
                            end = Offset(size.width, size.height),
                            strokeWidth = 1.dp.toPx()
                        )
                    }
                    .padding(vertical = 12.dp, horizontal = 8.dp)

                val clickableModifier = if (rowOnClick != null) {
                    rowModifier.clickable { rowOnClick(rowIndex) }
                } else {
                    rowModifier
                }

                Row(modifier = clickableModifier) {
                    rowBuilder(rowIndex)
                }

                HorizontalDivider(
                    color = Color.LightGray,
                    thickness = 1.dp,
                )
            }
        }
    }
}

@Composable
fun RowScope.AppTableCell(content: @Composable ColumnScope.() -> Unit) {
    Column(
        modifier = Modifier
            .weight(1f)
            .padding(vertical = 0.dp, horizontal = 4.dp),
        content = content
    )
}