<?php

/**
 * This web page allows users to change their password.
 *
 * @author James Chen
 */

require_once 'minimal.php';

/**
 * Change account password.
 *
 * This API
 *
 * @api
 * @example
 *
 *     $data = [
 *         'email' => 'user@example.com',
 *         'old_password' = '123456',
 *         'new_password' = '654321',
 *     ];
 *
 * @author James Chen
 */
handle(HttpMethod::POST, function ($data) {
    $email = $data["email"];
    $account = get_account_by_email($email);
    if ($account === null) {
        throw new RuntimeException(
            "Account associated with [$email] does not exist."
        );
    }

    if ($data["old_password"] !== $account["password"]) {
        throw new RuntimeException(
            "Old password does not match [$email]."
        );
    }

    change_password($email, $data["new_password"]);
    success("Changed password successfully.");
});

?>

<html lang="en">
<head>
  <title>Change Password</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <h3>Change Password</h3>

    <form action="change_password.php" method="POST">
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

