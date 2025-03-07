## Phase II

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

### Tasks

#### 1. Create Student Account and Update Student Information

- Users can create a student account on `create_student_account.php`.
    - This involves creating an `account` record, a `student` record, and a specific student record of `undergraduate`, `master`, or `PhD`.
    - If created successfully, the user will be redirected to `student.php`.
    - If the email has been registered, the user will be redirected to the error page.
- Users can change the password for a student account on `change_password.php`.
    - If the account does not exist or the old password does not match, the user will be redirected to the error page. 

#### 2. 