<?php

require_once "../minimal.php";

/**
 * Allows users to login.
 *
 * @param_post studentType The type of the student.
 * @param_post email The student email.
 * @param_post password The password of the account.
 * @param_post name The student's name;
 * @param_post deptName The name of the department.
 * @api
 * @author James
 */
handle(HttpMethod::POST, function (array $data) {
    $studentType = require_field($data, 'studentType');
    if (!in_array($studentType, [
        StudentType::UNDERGRADUATE,
        StudentType::MASTER,
        StudentType::PHD
    ])) {
        error_response("Invalid student type.");
    }

    $email = require_field($data, 'email');
    $password = require_field($data, 'password');
    $name = require_field($data, 'name');
    $deptName = require_field($data, 'deptName');

    $studentId = create_student_account($studentType, $email, $password, $name, $deptName);
    success_response("Created student account.", [
        "studentId" => $studentId
    ]);
});
