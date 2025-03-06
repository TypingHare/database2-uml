<?php /** @noinspection DuplicatedCode */

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    try {
        // Connect to database and get the PDO (PHP Data Object)
        $config = require __DIR__ . '/common/config.php';
        $database_config = $config['database'];
        $host = $database_config['host'];
        $dbname = $database_config['dbname'];
        $username = $database_config['username'];
        $password = $database_config['password'];

        $pdo = new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );

        // Get all time slots
        $stmt = $pdo->prepare(
            "
                SELECT time_slot_id FROM time_slot
            "
        );
        $stmt->execute();
        $last_time_slot = $stmt->fetchAll();
        $time_slot_ids = array_column($last_time_slot, 'time_slot_id');
        $time_slot_ids = array_map(fn ($str) => (int)substr($str, 2), $time_slot_ids);
        $max_id = max($time_slot_ids);

        // Create a new time slot
        $stmt = $pdo->prepare(
            "
                INSERT INTO time_slot (time_slot_id, day, start_time, end_time)
                VALUES (:time_slot_id, :day, :start_time, :end_time)
            "
        );

        $stmt = $pdo->prepare(
            "
                INSERT INTO section (
                    course_id, 
                    section_id, 
                    semester, 
                    year, 
                    instructor_id, 
                    classroom_id, 
                    time_slot_id
                ) VALUES (
                    :course_id,
                    :section_id,
                    :semester,
                    :year,
                    :instructor_id,
                    :classroom_id,
                    :time_slot_id
                )
            "
        );
        $stmt->bindParam(":course_id", $_POST["course_id"]);
        $stmt->bindParam(":section_id", $_POST["section_id"]);
        $stmt->bindParam(":semester", $_POST["semester"]);
        $stmt->bindParam(":year", $_POST["year"]);
        $stmt->bindParam(":instructor_id", $_POST["instructor_id"]);
        $stmt->bindParam(":classroom_id", $_POST["classroom_id"]);
        $stmt->bindParam(":time_slot_id", $_POST["time_slot_id"]);
        $stmt->execute();

        echo json_encode([
            'status' => "success",
            'message' => "Created course section successfully."
        ]);
    } catch (Exception $ex) {
        echo json_encode([
            'status' => "error",
            'message' => $ex->getMessage()
        ]);
    }
}
