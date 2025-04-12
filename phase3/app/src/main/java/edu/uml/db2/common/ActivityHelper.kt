package edu.uml.db2.common

import android.app.Activity
import android.content.Context
import android.content.Intent
import kotlin.reflect.KClass

/**
 * Finishes the given activity if the context is an instance of [Activity].
 *
 * @param context The context of the current activity to be finished.
 * @author James Chen
 */
fun finishActivity(context: Context) {
    if (context is Activity) {
        context.finish()
    }
}

/**
 * Starts a new activity.
 *
 * @param context The context used to start the activity.
 * @param newActivity The class reference of the activity to start.
 * @param finish Whether to finish the current activity after starting the new one.
 * @author James Chen
 */
fun startActivity(context: Context, newActivity: KClass<*>, finish: Boolean = false) {
    context.startActivity(Intent(context, newActivity.java))
    if (finish) finishActivity(context)
}