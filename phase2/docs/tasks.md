## Tasks

In this document, write down what you have implemented in detail.

### 1. Create Student Account and Update Student Information

- Users can create a student account on `create_student_account.php`.
    - This involves creating an `account` record, a `student` record, and a specific student record of `undergraduate`, `master`, or `PhD`.
    - If created successfully, the user will be redirected to `student.php`.
    - If the email has been registered, the user will be redirected to the error page.
- Users can change the password for a student account on `change_password.php`.
    - If the account does not exist or the old password does not match, the user will be redirected to the error page.

### 2. Admin Views, Creates and Edits Sections

- Admin can view all the course sections on `section.php`.
- Admin can create a course section on `create_section.php`.
  - In the same semester, no time slots can be assigned to more than two sections.
  - In the same semester, no instructor can be assigned to more than two sections.
  - In the same semester, an instructor can be assigned to two sections, but these two sections must be in the same classroom and have two consecutive time slots.
- Admin can edit a course section by clicking the `edit` button in the section table in `section.php`. The admin will then be navigated to `edit_section_php`, where they can edit the instructor, classroom, and time slot.