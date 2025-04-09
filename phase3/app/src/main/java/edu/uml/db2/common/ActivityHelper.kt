package edu.uml.db2.common

import android.app.Activity
import android.content.Context
import android.content.Intent
import kotlin.reflect.KClass

fun finishActivity(context: Context) {
    if (context is Activity) {
        context.finish()
    }
}

fun startActivity(context: Context, newActivity: KClass<*>, finish: Boolean = false) {
    context.startActivity(Intent(context, newActivity.java))
    if (finish) finishActivity(context)
}