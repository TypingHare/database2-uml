## Phase II

### Deployment Instruction

This application requires the following environment requirements:

* PHP - `8.2.x`
* MariaDB - `15.x`
* Apache (httpd) - `2.4.x`

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