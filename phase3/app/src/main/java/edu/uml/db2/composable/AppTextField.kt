package edu.uml.db2.composable

import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.material3.OutlinedTextField
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.input.PasswordVisualTransformation
import androidx.compose.ui.text.input.VisualTransformation

/**
 * This composable function represents a text field with a specified placeholder and value. If the
 * password is set to true, the characters are replaced with asterisks visually. The `onValueChange`
 * is fired when the value of the text field is changed.
 *
 * @author James Chen
 */
@Composable
fun AppTextField(
    placeholder: String, value: String, isPassword: Boolean = false, onValueChange: (String) -> Unit
) = OutlinedTextField(
    modifier = Modifier.fillMaxWidth(),
    value = value,
    onValueChange = onValueChange,
    label = { Text(placeholder) },
    visualTransformation = if (isPassword) PasswordVisualTransformation() else VisualTransformation.None
)