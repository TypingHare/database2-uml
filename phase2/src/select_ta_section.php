<?php

require_once 'minimal.php';



function get_ta_section(): array
{
    $stmt = pdo_instance()->prepare(
        "
            SELECT *
            FROM section s, take t
            WHERE s.section_id = t.section_id
            AND s.semester = t.semester
            AND s.year = t.year
            GROUP BY t.student_id, t.course_id, t.section_id, semester, year
            HAVING COUNT(t.student_id) > 10;
        "
    );
    execute($stmt);

    return $stmt->fetchAll();
}

$sections_for_ta = get_ta_section();
$sections = get_all_sections();

$student_id = $_GET['student_id'];
$student_name = get_student_by_id($student_id);
//var_dump($student);

//var_dump($sections);



//$instructor_id = $_GET['student_id'] ?? '';
function get_edit_url(array $sections_for_ta): string
{
    return build_url(Page::ASSIGN_TA, [
        'student_id' => $_GET['student_id'],
        'course_id' => $sections_for_ta['course_id'],
        'section_id' => $sections_for_ta['section_id'],
        'semester' =>  $sections_for_ta['semester'],
        'year' =>  $sections_for_ta['year'] ?? ''
    ]);
}
?>


<html lang="en">
<head>
    <title>TA Sections</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 0.5rem;
        }
    </style>
</head>

<body style="height: 100%;">

    <div style="display: flex; justify-content: center; margin-top: 16vh;">
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <h3>PhD Student</h3>
            <div><b>Student ID: </b> <?= $student_id ?></div>
            <div><b>Student Name: </b> <?= $student_name['name'] ?></div>
            <h2>Sections</h2>
            <table style="width:100%;">
                <tr>
                    <td>Course ID</td>
                    <td>Section ID</td>
                    <td>Semester</td>
                    <td>Year</td>
                    <td style="color: grey;">Operation</td>
                </tr>
                <?php foreach ($sections as $section): ?>
                <tr>
                    <td><?= $section['course_id'] ?>
                    </td>
                    <td><?= $section['section_id'] ?>
                    </td>
                    <td><?= $section['semester'] ?>
                    </td>
                    <td><?= $section['year'] ?>
                    </td>
                    <td>
                        <a href="<?= get_edit_url($section) ?>">
                            <button>Select</button>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

            <div style="display: flex; gap: 0.5rem;">
                <a href="<?= Page::SELECT_PHD ?>">
                    <button type="button">Back</button>
                </a>
            </div>
        </div>
    </div>

</body>
</html>




