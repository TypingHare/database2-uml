<?php

/**
 * This file serves as a page template file. You can copy this template to
 * your newly created file, remove the comments, and add elements in the
 * specific div.
 *
 * NOTE: Make sure to include a brief introduction to the file and change the
 * author name.
 *
 * @author James Chen
 */

require_once 'minimal.php';

?>

<html lang="en">
<head>
  <title>[Title]</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <h2>[Page Title]</h2>
    <form
      style="display: flex; flex-direction: column; gap: 1rem;"
      action="<?= Page::ERROR ?>"
      method="POST"
    >
      <!-- Add form elements here. All elements are arranged vertically with a
           spacing of 1rem. -->
    </form>

    <!-- Add other elements. -->
  </div>
</div>

</body>
</html>
