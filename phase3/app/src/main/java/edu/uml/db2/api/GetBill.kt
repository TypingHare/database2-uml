package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import edu.uml.db2.common.StudentBillDto
import edu.uml.db2.common.StudentDto
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

/**
 * Get the student's bill.
 *
 * @see StudentDto
 * @author James Chen
 */
@OptIn(InternalSerializationApi::class)
<<<<<<< HEAD:phase3/app/src/main/java/edu/uml/db2/api/GetBill.kt
fun getBill(
=======
fun getStudentBill(
>>>>>>> 5c3ab7a (finished: billActivity functionalities):phase3/app/src/main/java/edu/uml/db2/api/GetStudentBill.kt
    studentId: String,
    semester: String,
    year: String,
    callback: ResponseCallback<StudentBillDto>
) {
    Server.get(
        Endpoint.GET_BILL,
        StudentBillDto.serializer(),
        Parameters.build {
            append("studentId", studentId)
            append("semester", semester)
            append("year", year)
        },
        callback
    )
}