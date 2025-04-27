# [Database II Coursework](https://github.com/TypingHare/database2-uml)

This repository houses the coursework for the Database II course at UMass Lowell.

**Date**: Jan 28, 2025 ~ ongoing

**Group Members**:

- [James Chen](zhuojian_chen@student.uml.edu)
- [Victor Reust]()
- [Alexis Marx]()

## Environment Requirements

* PHP - `8.2.x`
* MariaDB - `15.x`
* Apache (httpd) - `2.4.x`
* GNU Make - `3.x`
* PHP CS Fixer - `3.70.x`

## Contribution Guidelines

All contributors to this repository are required to adhere to the following guidelines:

1. **Branching and Pull Requests**

   * The default branch for this repository is `main`.

   * Contributors should create a new branch from the `main` branch for their work. Each branch should represent a specific feature, bug fix, or improvement.

   * When you have completed your work, submit a pull request to merge your branch into the `main` branch.

   * Branch names should be descriptive and follow the format:

     ~~~text
     <developer-name>-<task-description>
     ~~~

     Example of branch names:

     - `james-create-homepage`
     - `andrew-delete-legacy-code`
     - `victor-improve-sql`

   * Ensure the branch name clearly indicates the developer’s identity and the purpose of the branch.

2. **Commit Messages**

   - Provide clear, descriptive commit messages to explain the changes.

   - Follow a consistent structure for commit messages, such as:

     ```text
     <type>(<scope>): <description>
     ```

     Example of commit messages:

     - `feat(auth): add login functionality`
     - `fix(api): resolve 500 error on data fetch`
     - `docs(readme): update installation instructions`

3. **Comment**

   * All global variables, functions, and classes in PHP must be documented using **PHPDoc comments**. Use comments to provide clarity about the purpose, functionality, and context of the code.

   * Class methods should include comments with at least one descriptive sentence, except for trivial methods like getters and setters.

   * You may add your name in the PHPDoc comments as an **`@author`** tag for files, classes, functions, or variables you create. Adding your name establishes responsibility for the corresponding code section and discourages unauthorized updates by other developers. If you need to modify a section of code under another developer's scope, request permission and have your name added to the **`@author`** tag.

     ```php
     /**
      * Represents a registered student in the system.
      * @author James Chen
      */
     class Student {
         // Code goes here
     }
     ```

4. **Code Style**

   * All PHP code should conform to the [PHP coding standards](https://www.drupal.org/docs/develop/standards/php/php-coding-standards) to maintain consistency and readability across the codebase. Overrides to the PHP Standards:
     - Use **4 spaces** for indentation instead of 2 spaces to improve code clarity.
