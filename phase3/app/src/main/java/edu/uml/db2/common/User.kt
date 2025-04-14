package edu.uml.db2.common

import android.content.Context
import android.util.Log

const val USER_INFO_FILE_NAME = "user_info.txt"

data class User(val type: UserType, val id: String?)

fun saveUser(context: Context, user: User) {
    val content = "${user.type}\n${user.id ?: ""}"
    context.openFileOutput(USER_INFO_FILE_NAME, Context.MODE_PRIVATE).use {
        it.write(content.toByteArray())
    }

    Log.i("SAVED_USER", user.toString())
}

fun removeUser(context: Context) {
    context.deleteFile(USER_INFO_FILE_NAME)
}

fun getUser(context: Context): User? {
    return try {
        val content = context.openFileInput(USER_INFO_FILE_NAME).bufferedReader()
            .useLines { lines -> lines.joinToString("\n") }
        val list = content.split("\n")
        val userType = UserType.valueOf(list.first())
        val id = list.getOrNull(1).takeIf { it != null && it.isNotBlank() }

        User(userType, id)
    } catch (ex: Exception) {
        Log.e("GET_USER", ex.toString(), ex)
        null
    }
}