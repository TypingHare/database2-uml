package edu.uml.db2.api

import edu.uml.db2.common.BillListDto
import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

/**
 * @api
 * @author James Chen
 */
@OptIn(InternalSerializationApi::class)
fun getStudentAllBills(studentId: String, callback: ResponseCallback<BillListDto>) {
    Server.get(Endpoint.GET_STUDENT_ALL_BILLS, BillListDto.serializer(), Parameters.build {
        append("studentId", studentId)
    }, callback)
}