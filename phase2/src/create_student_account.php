<?php

/**
 * This web page allows user to create a new student account.
 *
 * @author James Chen
 */

require_once 'service/department.php';

/**
 * Student account creating endpoint.
 *
 * This API Creates a student account. This includes creating an account record
 * with a "student" account type, creating a student record,
 *
 * @api
 * @param_post student_type The type of the student.
 * @param_post email The student email.
 * @param_post password The password of the account.
 * @param_post name The student's name;
 * @param_post dept_name The name of the department.
 * @see StudentType
 * @example
 *
 *     $data = [
 *         'email' => 'user@example.com',
 *         'name' => 'Alice',
 *         'dept_name => 'example department name',
 *         'student_type' => 'undergraduate'
 *     ];
 *
 * @author James Chen
 */
handle(HttpMethod::POST, function ($data) {
    $student_type = $data['student_type'];
    if (!in_array($student_type, [
        StudentType::UNDERGRADUATE,
        StudentType::MASTER,
        StudentType::PHD
    ])) {
        throw new RuntimeException('Invalid student type: ' . $student_type);
    }

    pdo_instance()->beginTransaction();
    $account = create_account($data["email"], $data["password"], AccountType::STUDENT);
    $student = create_student($account["email"], $data["name"], $data["dept_name"]);
    switch ($student_type) {
        case StudentType::UNDERGRADUATE:
            create_undergraduate($student['student_id']);
            break;
        case StudentType::MASTER:
            create_master($student['student_id']);
            break;
        case StudentType::PHD:
            create_phd($student['student_id']);
            break;
        default:
            pdo_instance()->rollBack();
    }
    pdo_instance()->commit();
    success('Created the account successfully.');

    redirect(Page::STUDENT, ['student_id' => $student["student_id"]]);
});

$departments = get_all_departments();
$student_types = [
    StudentType::UNDERGRADUATE,
    StudentType::MASTER,
    StudentType::PHD
];

?>

<html lang="en">
<head>
  <title>Create Student Account</title>
</head>
<body style="height: 100%;">

<div style="display: flex; justify-content: center; margin-top: 16vh;">
  <div>
    <h2>Create an account</h2>
    <form
      style="display: flex; flex-direction: column; gap: 1rem;"
      action="<?= Page::CREATE_STUDENT_ACCOUNT ?>" method="POST"
    >
      <div>
        <label for="email">Email: </label>
        <input type="email" name="email" id="email" required />
      </div>

      <div>
        <label for="password">Password: </label>
        <input type="password" name="password" id="password" required />
      </div>

      <div>
        <label for="name">Name: </label>
        <input type="text" name="name" id="name" required />
      </div>

      <div>
        <label for="dept_name">Department: </label>
        <select name="dept_name" id="dept_name">
            <?php foreach ($departments as $department): ?>
              <option
                value="<?= htmlspecialchars($department['dept_name']); ?>">
                  <?= htmlspecialchars($department['dept_name']); ?>
              </option>
            <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label for="student_type">Type: </label>
        <select name="student_type" id="student_type">
            <?php foreach ($student_types as $student_type): ?>
              <option value="<?= htmlspecialchars($student_type); ?>">
                  <?= htmlspecialchars($student_type); ?>
              </option>
            <?php endforeach; ?>
        </select>
      </div>

      <div style="display: flex; justify-content: center;">
        <button type="submit">Create</button>
      </div>
    </form>
  </div>
</div>

</body>
</html>
