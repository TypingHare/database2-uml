## Tests

Because we do not use any heavy framework in this project, it is very difficult
to write unit test for each page. So let us list all the test cases in this 
document and test them manually.

Users should be able to:

1. Log in as an admin in `index.html`. They should be redirected to `admin.php` if log in successfully.
2. Log in as an instructor in `index.html`. They should be redirected to `instructor.php` if log in successfully.
3. Create a student account in `create_student_account.php`. They should be redirected to `student.php` after creating the account.
4. Log in as a student in `index.html`. They should be redirected to `student.php` if log in successfully.
5. Navigate to the `change_password.php` in `student.php`.
6. Change password in `change_password.php`. They should be redirected to a dashboard page according to the account type after changing password or click the `Cancel` button.
7. Click the `Create section` button in `section.php` and navigate to `create_section.php`. Click the `Cancel` button in `create_section.php` and navigate back to `section.php`.
8. Create a legitimate section in `create_section.php`. They should be redirected to `section.php` after they finish.
   - s
9. Click any `Edit` button in `section.php` and navigate to `edit_section.php`. Click the `Cancel` button in `edit_section.php` and navigate back to `section.php`.
10. Edit the section in `edit_section.php`. They should be redirected to section `section.php` after they finish.