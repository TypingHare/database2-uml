<?php

/**
 * This web page allows admin to check their information and perform operations.
 *
 * @author James Chen
 */

require_once 'minimal.php';

/* need to add ability to assign TAs to sections
* TA restrictions are
* - PhD student
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
* can use course history from "Student transcript"
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
      <button>Manage sections</button>
    </a>

    <a href="<?= Page::ADVISOR ?>">
      <button>Manage advisors</button>
    </a>

    <a href="<?= Page::BILLS_SELECT_SEMESTER ?>">
      <button>View bills</button>
    </a>

    <a href="<?= Page::SELECT_PHD ?>">
      <button>Manage TAs</button>
    </a>
  </div>
</div>

</body>
</html>

