package edu.uml.db2.common

import kotlinx.serialization.ExperimentalSerializationApi
import kotlinx.serialization.InternalSerializationApi
import kotlinx.serialization.Serializable
import kotlinx.serialization.json.JsonIgnoreUnknownKeys

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
    val scholarship: Int,
    val hasScholarship: Boolean? = null
)

@Serializable
@InternalSerializationApi
data class CourseHistoryDto(
    val studentId: String,
    val courseId: String,
    val sectionId: String,
    val semester: String,
    val year: String,
    val grade: String? = null,
    val courseName: String,
    val credits: String
)

@Serializable
@InternalSerializationApi
data class CourseHistoryResponseDto(
    val currentList: List<CourseHistoryDto>, val completedList: List<CourseHistoryDto>
)

@Serializable
@InternalSerializationApi
data class CreateBillDto(val studentId: String)

@Serializable
@InternalSerializationApi
data class RegisterDto(
    val studentId: String,
    val courseId: String,
    val sectionId: String
)

@OptIn(ExperimentalSerializationApi::class)
@Serializable
@InternalSerializationApi
@JsonIgnoreUnknownKeys
data class CourseDto(
    val courseId: String,
    val sectionId: String,
    val instructorName: String,
    val day: String,
    val startTime: String,
    val endTime: String,
    val building: String,
    val roomNumber: String
)

@Serializable
@InternalSerializationApi
data class BillDto(
    val studentId: String, val semester: String, val year: String, val status: String
)

@Serializable
@InternalSerializationApi
data class CourseListDto(
    val list: List<CourseDto>
)

@Serializable
@InternalSerializationApi
data class BillListDto(val list: List<BillDto>)

@Serializable
@InternalSerializationApi
data class SectionDto(
    val studentId: String,
    val courseId: String,
    val sectionId: String,
    val semester: String,
    val year: String,
    val grade: String?,
    val courseName: String,
    val credits: String
)

@Serializable
@InternalSerializationApi
data class SectionListDto(
    val sections: List<SectionDto>
)

@Serializable
@InternalSerializationApi
data class ScholarshipDto(
    val studentId: String,
    val semester: String,
    val year: String,
    val scholarship: Int
)

@Serializable
@InternalSerializationApi
data class PayBillDto(val status: String)


@Serializable
@InternalSerializationApi
data class InstructorSectionListDto(
    val instructorSections: List<InstructorSectionDto>
)

@Serializable
@InternalSerializationApi
data class InstructorSectionDto(
    val courseId: String,
    val sectionId: String,
    val sections: List<SectionInstanceDto>
)

@Serializable
@InternalSerializationApi
data class SectionInstanceDto(
    val semester: String,
    val year: String,
    val students: List<StudentRecordDto>
)

@Serializable
@InternalSerializationApi
data class StudentRecordDto(
    val studentId: String,
    val name: String,
    val grade: String?
)

