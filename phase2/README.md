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
.                                     // Stores client files
├── api                               // Stores API files
│   ├── common                        // Common files
│   │   ├── constant.php              // Contains constant classes
│   │   ├── response.php              // Contains two response classes
│   │   ├── pdo.php                   // Contains helper functions related to PDO
│   │   ├── config.php                // Contains the configuration array
│   │   └── functional.php            // Contains some helper functions related to FP
│   │
│   ├── service                       // Stores service files
│   │   ├── account.php               // A service file containing functions related to accounts
│   │   └── ...                       // More service files
│   │
│   ├── minimal.php                   // The core file of Minimal
│   ├── create_student_account.php    // An API file that handles creating a student account
│   └── ...                           // More API files
│
├── index.php                         // Handles not found requests
├── create_student_account.php        // The client file for task 2.1
└── ...                               // More client files
```

### Core Concepts

- **Application Configuration Array**: All application configurations are stored as an array in `src/common/config.php`.
- **Client File**: PHP files that simulate client-side scenarios.
- **API File**: PHP files that handle HTTP requests.
- **Service File**: PHP files containing functions used across multiple API files.
- **Helper Function**: Utility functions frequently used throughout the codebase.