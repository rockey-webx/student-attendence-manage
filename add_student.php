<?php
require_once "admin_auth.php";
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $roll_no = intval($_POST["roll_no"]);
    $name = trim($_POST["name"]);

    $check = $conn->prepare(
        "SELECT id FROM students WHERE roll_no = ?"
    );

    $check->bind_param("i", $roll_no);
    $check->execute();

    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $message = "This Roll Number already exists!";

    } else {

        $stmt = $conn->prepare("
            INSERT INTO students
            (roll_no, name, class_name)
            VALUES (?, ?, 'FY - MCA')
        ");

        $stmt->bind_param("is", $roll_no, $name);

        if ($stmt->execute()) {
            $message = "Student added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Add Student</title>
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

.container {
    max-width: 550px;
    margin: 60px auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.12);
}

h1 {
    text-align: center;
    color: #8b0000;
    margin-bottom: 5px;
}

.class-name {
    text-align: center;
    color: #555;
    font-weight: bold;
    margin-bottom: 25px;
}

.success {
    background: #d1e7dd;
    color: #0f5132;
    padding: 12px;
    border-radius: 6px;
    text-align: center;
    font-weight: bold;
    margin-bottom: 20px;
}

.error {
    background: #f8d7da;
    color: #842029;
    padding: 12px;
    border-radius: 6px;
    text-align: center;
    margin-bottom: 20px;
}

label {
    display: block;
    margin-top: 15px;
    margin-bottom: 6px;
    font-weight: bold;
}

input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
}

.add-btn {
    width: 100%;
    margin-top: 25px;
    padding: 13px;
    border: none;
    border-radius: 6px;
    background: #8b0000;
    color: white;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
}

.add-btn:hover {
    background: #650000;
}

.list-btn {
    display: block;
    text-align: center;
    margin-top: 15px;
    padding: 12px;
    background: #555;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}

.back-home {
    text-align: center;
    margin-top: 20px;
}

.back-home a {
    display: inline-block;
    padding: 10px 20px;
    background: #8b0000;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 15px;
    font-weight: bold;
    transition: 0.3s;
}

.back-home a:hover {
    background: #650000;
    transform: translateY(-2px);
}

</style>

</head>

<body>

<div class="container">

    <h1>Add Student</h1>

    <div class="class-name">
        Class: FY - MCA
    </div>

    <?php if ($message): ?>

        <?php if (strpos($message, "successfully") !== false): ?>

            <div class="success">
                <?= htmlspecialchars($message) ?>
            </div>

        <?php else: ?>

            <div class="error">
                <?= htmlspecialchars($message) ?>
            </div>

        <?php endif; ?>

    <?php endif; ?>


    <form method="POST">

        <label>Roll Number</label>

        <input
            type="number"
            name="roll_no"
            placeholder="Enter Roll Number"
            required
        >

        <label>Student Name</label>

        <input
            type="text"
            name="name"
            placeholder="Enter Student Name"
            required
        >

        <button type="submit" class="add-btn">
            + Add Student
        </button>

    </form>


    <a href="students.php" class="list-btn">
        Student List
    </a>

</div>

<h2 class="back-home">
    <a href="admin_dashboard.php">Dashboard</a>
</h2>

</body>

</html>