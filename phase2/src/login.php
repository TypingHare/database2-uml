<?php

/**
 * The page allows users to sign in.
 *
 * @see api/login.php
 * @author James Chen
 */

?>

<html lang="en">
<head>
  <title>Login</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 10rem;">
  <div>
    <form action="api/login.php" method="POST">
      <div>
        <label for="email">Email: </label>
        <input type="email" name="email" id="email" required />
      </div>

      <div style="margin-top: 0.5rem;">
        <label for="password">Password: </label>
        <input type="password" name="password" id="password" required />
      </div>

      <div style="margin-top: 0.5rem; display: flex; justify-content: center;">
        <button type="submit">LOGIN</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>


