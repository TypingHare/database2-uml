package edu.uml.db2.common

import android.content.Context
import android.util.Log
import java.io.FileNotFoundException

/**
 * The name of the file that is used to store the user information.
 */
const val USER_INFO_FILE_NAME = "user_info.txt"

/**
 * User data class.
 */
data class User(val type: UserType, val id: String?)

/**
 * Saves a user into the user info file.
 *
 * Upon users sign in to the system, a user info file is created, and the user's type and ID
 * are written to the file. This allows the app to redirect the user to the dashboard page next time
 * the user open the app.
 *
 * This function is called in [edu.uml.db2.LoginActivity] and
 * [edu.uml.db2.CreateStudentAccountActivity].
 *
 * @author James Chen
 */
fun saveUser(context: Context, user: User) {
    val content = "${user.type}\n${user.id ?: ""}"
    context.openFileOutput(USER_INFO_FILE_NAME, Context.MODE_PRIVATE).use {
        it.write(content.toByteArray())
    }

    Log.i("SAVED_USER", user.toString())
}

/**
 * Deletes the user info file.
 *
 * This function is called when the user signs out.
 *
 * @author James Chen
 */
fun removeUser(context: Context) {
    context.deleteFile(USER_INFO_FILE_NAME)
}

/**
 * Gets the user info from the user info file, and returns a data object or null if the file does
 * not exist.
 *
 * This function is called when the dashboard activities ([edu.uml.db2.AdminActivity],
 * [edu.uml.db2.InstructorActivity], [edu.uml.db2.StudentActivity]) are created.
 *
 * @author James Chen
 */
fun getUser(context: Context): User? {
    return try {
        val content = context.openFileInput(USER_INFO_FILE_NAME).bufferedReader()
            .useLines { lines -> lines.joinToString("\n") }
        val list = content.split("\n")
        val userType = UserType.valueOf(list.first())
        val id = list.getOrNull(1).takeIf { it != null && it.isNotBlank() }

        User(userType, id)
    } catch (_: FileNotFoundException) {
        null
    } catch (ex: Exception) {
        Log.e("GET_USER", ex.toString())
        null
    }
}