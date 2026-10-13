<?php
require_once "db.php";

header("Content-Type: application/json");

if (!isset($_GET["roll_no"])) {
    echo json_encode([
        "success" => false
    ]);
    exit;
}

$roll_no = intval($_GET["roll_no"]);

$stmt = $conn->prepare("
    SELECT id, roll_no, name
    FROM students
    WHERE roll_no = ?
    LIMIT 1
");

$stmt->bind_param("i", $roll_no);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $student = $result->fetch_assoc();

    echo json_encode([
        "success" => true,
        "id" => $student["id"],
        "roll_no" => $student["roll_no"],
        "name" => $student["name"]
    ]);

} else {

    echo json_encode([
        "success" => false
    ]);
}
?>