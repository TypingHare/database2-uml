package edu.uml.db2.common

import android.app.Activity
import android.content.Context
import android.content.Intent
import kotlin.reflect.KClass

/**
 * Finishes the given activity if the context is an instance of [Activity]. This will remove the
 * given activity from the activity stack.
 *
 * @param context The context (which should also be the activity) to be finished.
 * @author James Chen
 */
fun finishActivity(context: Context) {
    if (context is Activity) {
        context.finish()
    }
}

/**
 * Starts a new activity. This will push the new activity onto the activity stack. If `finish` is
 * true, then the current activity (context) will be removed from the activity stack after the new
 * activity is created.
 *
 * @param context The context used to start the activity.
 * @param newActivity The class reference of the activity to start.
 * @param finish Whether to finish the current activity after starting the new one.
 * @param intentUpdater A callback function that updates the
 * @author James Chen
 */
fun startActivity(
    context: Context,
    newActivity: KClass<*>,
    finish: Boolean = false,
    intentUpdater: ((intent: Intent) -> Unit)? = null
) {
    context.startActivity(Intent(context, newActivity.java).apply { intentUpdater?.invoke(this) })
    if (finish) finishActivity(context)
}