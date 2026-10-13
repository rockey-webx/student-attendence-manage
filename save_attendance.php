<?php
require_once "db.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request"
    ]);
    exit;
}

$lecture_id = intval($_POST["lecture_id"] ?? 0);
$student_id = intval($_POST["student_id"] ?? 0);
$status = $_POST["status"] ?? "";

if ($lecture_id <= 0 || $student_id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid lecture or student"
    ]);
    exit;
}

if ($status !== "Present" && $status !== "Absent") {
    echo json_encode([
        "success" => false,
        "message" => "Invalid attendance status"
    ]);
    exit;
}

/* Check student exists */
$check = $conn->prepare(
    "SELECT id FROM students WHERE id = ?"
);

$check->bind_param("i", $student_id);
$check->execute();

$result = $check->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Student not found"
    ]);
    exit;
}

/* Save / update attendance */
$stmt = $conn->prepare("
    INSERT INTO attendance
    (lecture_id, student_id, status)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE
    status = VALUES(status)
");

$stmt->bind_param(
    "iis",
    $lecture_id,
    $student_id,
    $status
);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Attendance saved"
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $stmt->error
    ]);
}
?>