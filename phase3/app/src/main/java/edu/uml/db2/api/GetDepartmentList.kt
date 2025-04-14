package edu.uml.db2.api

import edu.uml.db2.common.DepartmentListDto
import edu.uml.db2.common.Endpoint
import edu.uml.db2.common.ResponseCallback
import edu.uml.db2.common.Server
import kotlinx.serialization.InternalSerializationApi

/**
 * Gets a list of departments.
 */
@OptIn(InternalSerializationApi::class)
fun getDepartmentList(callback: ResponseCallback<DepartmentListDto>) {
    Server.get(Endpoint.GET_DEPARTMENT_LIST, DepartmentListDto.serializer(), callback = callback)
}