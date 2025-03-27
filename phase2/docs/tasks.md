## Tasks

In this document, write down what you have implemented in detail.

### 1. Create a Student Account and Update Student Information

- Users can create a student account on `create_student_account.php`.
    - This involves creating an `account` record, a `student` record, and a specific student record of `undergraduate`, `master`, or `PhD`.
    - If created successfully, the user will be redirected to `student.php`.
    - If the email has been registered, the user will be redirected to the error page.
- Users can change the password for a student account on `change_password.php`.
    - If the account does not exist or the old password does not match, the user will be redirected to the error page.


### 2. Admin Views, Creates, and Edits Sections

- Admin can view all the course sections on `section.php`.
- Admin can create a course section on `create_section.php`. If any of the following conditions is not met after the section is created, the section will not be created. After the creation, the user will be redirected back to `section.php`.
  - In the same semester, there are more than two sections associated with the same time slot.
  - In the same semester, there are more than two sections associated with the same instructor.
  - In the same semester, there would not exist two sections associated with the same classroom and time slot.
  - In the same semester, there would not exist two sections associated with the same instructor but different classrooms.
  - In the same semester, if an instructor takes two different sections, then the time slots associated with the two sections should be consecutive—the gap between two time slots is less than or equal to 15 minutes.
- Admin can edit a course section by clicking the `edit` button in the section table in `section.php`. The admin will then be navigated to `edit_section_php`, where they can edit the instructor, classroom, and time slot. A successful edition should meet all the conditions aforementioned. After the edition, the user will be redirected back to `section.php`.


### 6. Teaching Assistants
-Admin can select a student to be a TA for a section.
  -Students must be type PhD.
  -Sections must have > 10 students enrolled.
  -Student can only serve as a TA for one section(per semester?)
-"Select TA" button on admin page that takes user to select_phd_student.php page.
  -Drop down menu to select student.
-"Select Section" button that takes user to select_section.php.
  -Carry student ID to this URL and display it at top.
  -Table of all availablesections to choose from.
  -Last column will have an "apply" button.
  -Student id and section info added to TA table on click.
-User taken to TA page on click.
  -table of all TAs
  -Back button that takes user back to admin page.


### 8. Assigning Advisors for PhD Students


### 9. Next Semester Course Suggestion 
- Student can prompt the system to suggest classes for their next semester. The system will suggest three classes that student can take based on their degree pathway and completed courses.
  - Suggested classes will adhere to class prerequisites.
  - Classes without prerequisites can be suggested.
  - Full classes will be omitted. (15 students)
  - Failed classes will be suggested(possibly prioritized if not too difficult).
- Student can then select 1 - 3 of them and add them to their registered classes or decline the suggestion. There's a possibility that registering with this info is too complicated for this project, so the simplified version will just have a back button on the suggestion page.
 - "Suggest Classes" button on the student page that takes them to the `suggested_courses.php` page.
 - 3 suggested classes will be selectable (clickable checkbox).
 - Apply button needed to register selected classes. Ideally, it goes to the course_select.php  page with the selected courses pulled up. If it is too difficult, the button can take them to the course selection page. 
 - Back button to return student to student.php page.


### 10. Bill System

- The admin can click the `View bills` button on the dashboard page to navigate to `bills_select_semester.php`, where the admin is required to select a specific semester.
- The admin can click the `View bills` button in `bills_select_semester.php` to navigate to `bills.php` to view the bill status and scholarship of all students in the selected semester. The admin can also click the `Back` button to return back to the dashboard page.
- Admin can click the `Create` button in the table in `bills.php` to create a bill for a specific student.
- Admin can click the `Reward` button in the table in `bills.php` to reward a scholarship for a specific student.
- After a bill is issued, the student will receive a hold on the dashboard page.
- Students can click the `View bills` button on the dashboard page to navigate to `student_bills.php`, where students can see the bills for all semesters, including both paid and unpaid bills.
- Students can click the `View details` button in the table in `student_bills.php` to view the details of a specific bill. The student will then be navigated to `bill_payment.php`, where all the courses that are registered in that semester are displayed. Each credit is valued at `800` dollars. The amount that the student has to pay is equal to the total tuition minus the scholarship.
- Students can click the `Pay` button in `bill_payment.php` to pay the bill. They will be redirected to `payment_success.php`, which displays the payment success message, and then redirected to the dashboard page in five seconds. They can also click the `Cancel` button in `bill_payment.php` to return to `student_bills.php`.