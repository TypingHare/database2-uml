package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import edu.uml.db2.common.StudentBillListDto
import io.ktor.http.Parameters
import kotlinx.serialization.InternalSerializationApi

@OptIn(InternalSerializationApi::class)
fun getBills(semester: String, year: String, callback: ResponseCallback<StudentBillListDto>) {
    Server.get(Endpoint.GET_BILLS, StudentBillListDto.serializer(), Parameters.build {
        append("semester", semester)
        append("year", year)
    }, callback)
}