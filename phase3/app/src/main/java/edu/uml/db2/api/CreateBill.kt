package edu.uml.db2.api

import edu.uml.db2.common.CreateBillDto
import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

@OptIn(InternalSerializationApi::class)
fun createBill(
    studentId: String, semester: String, year: String, callback: ResponseCallback<CreateBillDto>
) {
    Server.post(Endpoint.CREATE_BILL, CreateBillDto.serializer(), Parameters.build {
        append("studentId", studentId)
        append("semester", semester)
        append("year", year)
    }, callback)
}