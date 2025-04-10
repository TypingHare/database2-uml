package edu.uml.db2.dto

import kotlinx.serialization.InternalSerializationApi
import kotlinx.serialization.Serializable

@Serializable
@InternalSerializationApi
data class DepartmentListDto(
    val list: List<DepartmentDto>
)

@Serializable
@InternalSerializationApi
data class DepartmentDto(
    val deptName: String,
    val location: String
)