<?php

/**
 * This web page allows user to create a new student account.
 *
 * @author James Chen
 */

require_once 'api/service/department.php';

$departments = get_all_departments();

?>

<html lang="en">
<head>
  <title>Create Student Account</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 10rem;">
  <div>
    <h3>CREATE AN ACCOUNT</h3>

    <form action="api/create_student_account.php" method="POST">
      <div>
        <label for="email">Email: </label>
        <input type="email" name="email" id="email" required />
      </div>

      <div style="margin-top: 0.5rem;">
        <label for="password">Password: </label>
        <input type="password" name="password" id="password" required />
      </div>

      <div style="margin-top: 0.5rem;">
        <label for="name">Name: </label>
        <input type="text" name="name" id="name" required />
      </div>

      <div style="margin-top: 0.5rem;">
        <label for="dept_name">Department: </label>
        <select name="dept_name" id="dept_name">
            <?php foreach ($departments as $department): ?>
              <option
                value="<?php echo htmlspecialchars($department['dept_name']); ?>">
                  <?php echo htmlspecialchars($department['dept_name']); ?>
              </option>
            <?php endforeach; ?>
        </select>
      </div>

      <div style="margin-top: 0.5rem; display: flex; justify-content: center;">
        <button type="submit">CREATE</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
