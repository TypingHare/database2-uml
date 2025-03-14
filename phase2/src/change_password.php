<?php

/**
 * This page allows users to change their password.
 *
 * @param_get email The email of the user.
 * @author James Chen
 */

require_once 'minimal.php';

$email = $_GET["email"] ?? $_POST["email"];
$account = get_account_by_email($email);
if ($account === null) {
    throw new RuntimeException(
        "Account associated with [$email] does not exist."
    );
}
$back_page_url = match ($account['type']) {
    AccountType::ADMIN => Page::ADMIN,
    AccountType::INSTRUCTOR => build_url(Page::INSTRUCTOR, [
        'instructor_id' => get_instructor_by_email($account["email"])["instructor_id"],
    ]),
    AccountType::STUDENT => build_url(Page::STUDENT, [
        'student_id' => get_student_by_email($account["email"])["student_id"]
    ]),
    default => throw new RuntimeException('Unknown account type.'),
};

/**
 * Change account password.
 *
 * This API allows user to change the password.
 *
 * @api
 * @param_post email The email of the user.
 * @param_post old_password The current password for the account.
 * @param_post new_password The new password to set.
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
handle(HttpMethod::POST, function ($data) use ($account, $email, $back_page_url) {
    if ($data["old_password"] !== $account["password"]) {
        throw new RuntimeException(
            "Old password does not match [$email]."
        );
    }

    change_password($email, $data["new_password"]);
    success("Changed password successfully.");

    redirect($back_page_url);
});

?>

<html lang="en">
<head>
  <title>Change Password</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <h3>Change password</h3>
    <form
      style="display: flex; flex-direction: column; gap: 1rem;"
      action="<?= Page::CHANGE_PASSWORD ?>"
      method="POST"
    >
      <div>
        <label for="email">Email: </label>
        <input type="email" name="email" id="email"
               value="<?= $_GET['email'] ?? ""; ?>" required />
      </div>

      <div>
        <label for="old_password">Old Password: </label>
        <input type="password" name="old_password" id="old_password" required />
      </div>

      <div>
        <label for="new_password">New Password: </label>
        <input type="password" name="new_password" id="new_password" required />
      </div>

      <div style="display: flex; justify-content: center;">
        <button type="submit">Update</button>
        <a style="margin-left: 1rem" href="<?= $back_page_url ?>">
          <button type="button">Cancel</button>
        </a>
      </div>
    </form>
  </div>
</div>

</body>
</html>

