<?php
require_once "admin_auth.php";
require_once "db.php";

$result = $conn->query("
    SELECT * FROM students
    ORDER BY roll_no ASC
");
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student List - FY MCA</title>
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
    max-width: 1000px;
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.12);
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

h1 {
    margin: 0;
    color: #8b0000;
}

.class-name {
    margin-top: 6px;
    color: #555;
    font-weight: bold;
}

.add-btn {
    background: #8b0000;
    color: white;
    padding: 11px 18px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
}

.add-btn:hover {
    background: #650000;
}

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #8b0000;
    color: white;
    padding: 13px;
    text-align: left;
}

td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

tr:hover {
    background: #f8f8f8;
}

.edit-btn {
    display: inline-block;
    background: #0d6efd;
    color: white;
    padding: 7px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
}

.delete-btn {
    display: inline-block;
    background: #dc3545;
    color: white;
    padding: 7px 12px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    margin-left: 5px;
}

.edit-btn:hover {
    background: #0b5ed7;
}

.delete-btn:hover {
    background: #bb2d3b;
}

.empty {
    text-align: center;
    padding: 30px;
    color: #777;
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

    <div class="header">

        <div>

            <h1>Student List</h1>

            <div class="class-name">
                Class: FY - MCA
            </div>

        </div>

        <a href="add_student.php" class="add-btn">
            + Add Student
        </a>

    </div>


    <div class="table-container">

        <table>

            <tr>

                <th>Roll No.</th>

                <th>Student Name</th>

                <th>Class</th>

                <th>Action</th>

            </tr>


            <?php if ($result && $result->num_rows > 0): ?>

                <?php while ($student = $result->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($student['roll_no']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($student['name']) ?>
                        </td>

                        <td>
                            FY - MCA
                        </td>

                        <td>

                            <a
                                href="edit_student.php?id=<?= $student['id'] ?>"
                                class="edit-btn"
                            >
                                Edit
                            </a>

                            <a
                                href="delete_student.php?id=<?= $student['id'] ?>"
                                class="delete-btn"
                                onclick="return confirm('Are you sure you want to delete this student?');"
                            >
                                Delete
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="4" class="empty">
                        No students added yet.
                    </td>

                </tr>

            <?php endif; ?>

        </table>

    </div>

</div>

<h2 class="back-home">
    <a href="admin_dashboard.php">Dashboard</a>
</h2>

</body>

</html>