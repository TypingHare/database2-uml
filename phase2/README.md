## Phase II

### Team Members

> - James Chen
> - Victor Reust
> - Alexis Marx

### Deployment Instruction

This application requires the following environment requirements:

* PHP - `>= 8.2.x`
* MariaDB - `>= 15.x`
* Apache (httpd) - `>= 2.4.x`

**You should import the `materials/db2_presentation.sql` to the database before checking the application.**

Move all the files in `src` to the *httpd main document root* (if using XAMPP, it should be `htdoc`), and visit the index file (`index.html`) by `localhost/`. On the index page, you are required to log in to the system. The application will redirect you to different dashboard pages based on the account type associated with the email. However, you can access different dashboard pages by the following URLs:

- Admin Dashboard Page: `localhost/admin.php`
- Instructor Dashboard Page: `localhost/instructor.php?instructor_id=1`
- Student Dashboard Page: `localhost/student.php?student_id=0102559623`

### Student-Proposed Functionalities

9\. Students can view their degree pathways, which consist of all the courses they must take to finish their degrees. They can also check what courses they can take in the upcoming semester based on their completed courses and the courses that are available in the next semester.

10\. Admin can issue a bill for a student. The student will then receive a hold and cannot sign up for courses, and they can remove the hold by making a payment. Students can view the bill history. Each bill contains some courses and the total tuition. Admin can give a student a scholarship based on their cumulative GPA, and the student will receive a deduction for their next bill.

### Folder Structure

The folder structure of the root directory of Phase II is as follows:

```php
.
├── src            // The source directory
├── materials      // Materials about Phase II on Blackboard
├── README.md      // (The current file)
└── Makefile       // Stores commonly used batch commands

```

The folder structure of the **source directory** is as follows:

```php
.                                 // The root of the source directory
├── common                        // Common files
│   ├── constant.php              // Contains constant classes
│   ├── response.php              // Contains two response classes
│   ├── pdo.php                   // Contains helper functions related to PDO
│   └── config.php                // Contains the configuration array
│
├── service                       // Stores service files
│   ├── account.php               // A service file containing functions related to accounts
│   └── ...                       // More service files
│
├── .htaccess                     // The .htaccess configuration file 
├── index.html                    // The entry point of the application
├── minimal.php                   // The core file of Minimal
├── template.php                  // A template page file
├── error.php                     // The destination page of errors
├── page_not_found.php            // The destination page of redirecting not-found pages
├── create_student_account.php    // One of the page files for task 2.1
└── ...                           // More page files
```

### Other Documentations

- [tasks.md](docs/tasks.md)
- [tests.md](docs/tests.md)
- [print_out.md](docs/print_out.md)