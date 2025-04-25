package edu.uml.db2.api

import android.util.Log
import edu.uml.db2.common.CourseListDto
import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import kotlinx.serialization.InternalSerializationApi

/**
 * Gets a list of current semester courses
 *
 * @see CourseListDto
 * @author Alexis Marx
 */
@OptIn(InternalSerializationApi::class)
fun getCourseList(callback: ResponseCallback<CourseListDto>) {
    Log.d("BREADCRUMB", "getCourseList function called")
    Server.get(Endpoint.GET_COURSE_LIST, CourseListDto.serializer(), callback = callback)
//    Server.get(Endpoint.GET_COURSE_LIST, CourseListDto.serializer()) { res, isSuccess ->
//        Log.d("BREADCRUMB", "Server response: ${res.message}")
//        Log.d("BREADCRUMB", "Raw Response Body: ${res.data}")
//        if (!isSuccess) {
//            Log.e("BREADCRUMB", "Error: ${res.message}")
//        }
//        callback(res, isSuccess)
//    }

}