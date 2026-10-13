<?php
require_once "admin_auth.php";
require_once "db.php";

if (!isset($_GET["id"])) {
    die("Student ID missing.");
}

$id = intval($_GET["id"]);

$stmt = $conn->prepare(
    "SELECT * FROM students WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Student not found.");
}

$student = $result->fetch_assoc();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $roll_no = intval($_POST["roll_no"]);
    $name = trim($_POST["name"]);

    // Check whether another student already has this roll number
    $check = $conn->prepare(
        "SELECT id FROM students WHERE roll_no = ? AND id != ?"
    );

    $check->bind_param("ii", $roll_no, $id);
    $check->execute();

    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {

        $message = "This Roll Number already belongs to another student.";

    } else {

        $update = $conn->prepare("
            UPDATE students
            SET roll_no = ?, name = ?
            WHERE id = ?
        ");

        $update->bind_param("isi", $roll_no, $name, $id);

        if ($update->execute()) {

            header("Location: students.php");
            exit;

        } else {

            $message = "Error: " . $update->error;

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Student</title>
<link rel="stylesheet" href="responsive.css">

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f9;
}

.box {
    max-width: 500px;
    margin: 60px auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.12);
}

h1 {
    text-align: center;
    color: #8b0000;
}

.class-name {
    text-align: center;
    color: #555;
    font-weight: bold;
    margin-bottom: 25px;
}

label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}

input {
    width: 100%;
    padding: 12px;
    margin-top: 6px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
}

button {
    width: 100%;
    margin-top: 22px;
    padding: 13px;
    background: #8b0000;
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    font-size: 16px;
    cursor: pointer;
}

.error {
    background: #f8d7da;
    color: #842029;
    padding: 10px;
    border-radius: 6px;
    text-align: center;
}

.back {
    display: block;
    text-align: center;
    margin-top: 15px;
    text-decoration: none;
    color: #333;
}

</style>

</head>

<body>

<div class="box">

    <h1>Edit Student</h1>

    <div class="class-name">
        Class: FY - MCA
    </div>

    <?php if ($message): ?>

        <div class="error">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>


    <form method="POST">

        <label>Roll Number</label>

        <input
            type="number"
            name="roll_no"
            value="<?= htmlspecialchars($student['roll_no']) ?>"
            required
        >


        <label>Student Name</label>

        <input
            type="text"
            name="name"
            value="<?= htmlspecialchars($student['name']) ?>"
            required
        >


        <button type="submit">
            Update Student
        </button>

    </form>


    <a href="students.php" class="back">
        ← Back to Student List
    </a>

</div>

</body>

</html>