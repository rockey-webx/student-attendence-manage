

<?php
require_once "admin_auth.php";
require_once "db.php";

if (!isset($_GET["id"])) {
    die("Student ID missing.");
}

$id = intval($_GET["id"]);

$stmt = $conn->prepare(
    "DELETE FROM students WHERE id = ?"
);

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: students.php");
    exit;
}

echo "Unable to delete student.";

?>