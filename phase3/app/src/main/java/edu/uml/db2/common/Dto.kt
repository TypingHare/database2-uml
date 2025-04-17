package edu.uml.db2.common

import kotlinx.serialization.InternalSerializationApi
import kotlinx.serialization.Serializable

@Serializable
@InternalSerializationApi
data class CreateStudentAccountDto(val studentId: String)

@Serializable
@InternalSerializationApi
data class DepartmentListDto(
    val list: List<DepartmentDto>
)

@Serializable
@InternalSerializationApi
data class DepartmentDto(
    val deptName: String, val location: String
)

@Serializable
@InternalSerializationApi
data class LoginDto(
    val email: String,
    val type: String,
    val studentId: String? = null,
    val instructorId: String? = null
)

@Serializable
@InternalSerializationApi
data class StudentDto(
    val studentType: String,
    val studentId: String,
    val name: String,
    val email: String,
    val deptName: String,
    val subclass: StudentSubClassDto
)

@Serializable
@InternalSerializationApi
data class StudentSubClassDto(
    val studentId: String,
    val totalCredits: Int? = null,
    val classStanding: String? = null,
    val qualifier: String? = null,
    val proposalDefenceDate: String? = null,
    val dissertationDefenceDate: String? = null
)

@Serializable
@InternalSerializationApi
data class InstructorDto(
    val instructorId: String,
    val instructorName: String,
    val title: String,
    val deptName: String,
    val email: String,
)

@Serializable
@InternalSerializationApi
data class StudentBillListDto(
    val list: List<StudentBillDto>
)

@Serializable
@InternalSerializationApi
data class StudentBillDto(
    val studentId: String,
    val name: String,
    val email: String,
    val deptName: String,
    val semester: String,
    val year: String,
    val status: String,
    val scholarship: Int
)