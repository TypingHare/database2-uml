<?php

/**
 * This web page allows admin to check their information and perform operations.
 *
 * @author James Chen
 */

require_once 'minimal.php';

/* need to add ability to assign TAs to sections
* TA restrictions are
* - PHD student
* - section student count > 10
* - can only TA for 1 section
*
* count of students for each section_id in takes to get class size?
*/

/* need to add ability to assign 1 0r 2 instructor(s) as advisors to phd student
* Advisor has access to
* - start date
* - end date(optional)
* - course history
* - update student information?
* -
* can use course history from "Student transcipt"
* what information can be updated?
*/

?>

<html lang="en">
<head>
  <title>Admin Dashboard</title>
</head>
<body>

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div style="display: flex; flex-direction: column; gap: 1rem;">
    <a href="<?= Page::SECTION ?>">
      <button>Manage Sections</button>
    </a>

    <a href="<?= Page::ADVISOR ?>">
      <button>Manage Advisors</button>
    </a>
  </div>
</div>

</body>
</html>

