package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.PayBillDto
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

@OptIn(InternalSerializationApi::class)
fun payBill(
    studentId: String, semester: String, year: String, callback: ResponseCallback<PayBillDto>
) {
    Server.post(Endpoint.PAY_BILL, PayBillDto.serializer(), Parameters.build {
        append("studentId", studentId)
        append("semester", semester)
        append("year", year)
    }, callback)
}