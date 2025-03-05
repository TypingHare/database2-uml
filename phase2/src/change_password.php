<?php

/**
 * This web page allows users to change their password.
 *
 * @author James Chen
 */

?>

<html lang="en">
<head>
  <title>Change Password</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 10rem;">
  <div>
    <h3>Change Password</h3>

    <form action="api/change_password.php" method="POST">
      <div>
        <label for="email">Email: </label>
        <input type="email" name="email" id="email" required />
      </div>

      <div style="margin-top: 0.5rem;">
        <label for="old_password">Old Password: </label>
        <input type="password" name="old_password" id="old_password" required />
      </div>

      <div style="margin-top: 0.5rem;">
        <label for="new_password">New Password: </label>
        <input type="password" name="new_password" id="new_password" required />
      </div>

      <div style="margin-top: 0.5rem; display: flex; justify-content: center;">
        <button type="submit">UPDATE</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>

