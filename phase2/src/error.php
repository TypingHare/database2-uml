<?php

/**
 * The destination page of all errors. It displays an error message in red.
 *
 * @author James Chen
 */

$error_message = $_GET['error_message'];

?>

<html lang="en">
<head>
  <title>Error</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <b style="color: red;">[Error] <?= $error_message ?></b>
  </div>
</div>

</body>
</html>
