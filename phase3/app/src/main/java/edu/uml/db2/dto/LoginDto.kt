package edu.uml.db2.dto

import kotlinx.serialization.InternalSerializationApi
import kotlinx.serialization.Serializable

@Serializable
@InternalSerializationApi
data class LoginDto(
    val email: String,
    val type: String,
    val student: StudentDto? = null,
    val instructor: InstructorDto? = null
)

@Serializable
@InternalSerializationApi
data class StudentDto(
    val studentId: String, val name: String, val email: String, val deptName: String
)

@Serializable
@InternalSerializationApi
data class InstructorDto(
    val instructorId: String
)