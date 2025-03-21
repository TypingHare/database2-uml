## Tasks

In this document, write down what you have implemented in detail.

### 1. Create Student Account and Update Student Information

- Users can create a student account on `create_student_account.php`.
    - This involves creating an `account` record, a `student` record, and a specific student record of `undergraduate`, `master`, or `PhD`.
    - If created successfully, the user will be redirected to `student.php`.
    - If the email has been registered, the user will be redirected to the error page.
- Users can change the password for a student account on `change_password.php`.
    - If the account does not exist or the old password does not match, the user will be redirected to the error page.

### 2. Admin Views, Creates, and Edits Sections

- Admin can view all the course sections on `section.php`.
- Admin can create a course section on `create_section.php`. If any of the following conditions is not met after the section being created, the section will not be created. After the creation, the user will be redirected back to `section.php`.
  - In the same semester, there are more than two sections associated with the same time slot.
  - In the same semester, there are more than two sections associated with the same instructor.
  - In the same semester, there would not exist two sections associated with the same classroom and time slot.
  - In the same semester, there would not exist two sections associated with the same instructor but different classrooms.
  - In the same semester, if an instructor takes two different sections, then the time slots associated with the two sections should be consecutive—the gap between two time slots is less than or equal to 15 minutes.
- Admin can edit a course section by clicking the `edit` button in the section table in `section.php`. The admin will then be navigated to `edit_section_php`, where they can edit the instructor, classroom, and time slot. A successful edition should meet all the conditions aforementioned. After the edition, the user will be redirected back to `section.php`. 

### 8. Assigning Advisors for PhD Students

### 9. 