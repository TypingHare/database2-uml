<?php

/**
 * This page allows admin to select a specific semester, and it navigates the
 * admin to the `bills.php` page.
 *
 * @author James Chen
 */

require_once 'minimal.php';

$this_year = date('Y')

?>

<html lang="en">
<head>
  <title>Select Semester</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <h2>Select Semester</h2>
    <form
      style="display: flex; flex-direction: column; gap: 1rem;"
      action="<?= Page::BILLS ?>"
    >
      <label>
        Semester:
        <select name="semester">
          <option value="Fall" selected>Fall</option>
          <option value="Winter">Winter</option>
          <option value="Spring">Spring</option>
          <option value="Summer">Summer</option>
        </select>
      </label>

      <label>
        Year:
        <input type="number" name="year" min="2000" max="2099"
               value="<?= $this_year ?>" />
      </label>

      <div style="display: flex; justify-content: center; gap: 0.5rem;">
        <button type="submit">View bills</button>
        <a href="<?= Page::ADMIN ?>">
          <button type="button">Back</button>
        </a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
