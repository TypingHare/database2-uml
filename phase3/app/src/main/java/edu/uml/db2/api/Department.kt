package edu.uml.db2.api

import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.Server
import edu.uml.db2.dto.DepartmentListDto
import edu.uml.db2.dto.Response
import kotlinx.serialization.InternalSerializationApi

@OptIn(InternalSerializationApi::class)
fun getDepartmentList(callback: (res: Response<DepartmentListDto>, isSuccess: Boolean) -> Unit) {
    Server.get(Endpoint.GET_DEPARTMENT_LIST, DepartmentListDto.serializer(), callback = callback)
}