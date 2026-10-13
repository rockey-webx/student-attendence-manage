<?php

require_once "admin_auth.php";
require_once "db.php";

$result = $conn->query("
    SELECT
        id,
        roll_no,
        name
    FROM students
    ORDER BY roll_no ASC
");

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>Student Management</title>
<link rel="stylesheet" href="responsive.css">

<style>

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f9;
}

.container {
    width: 94%;
    max-width: 1000px;
    margin: 30px auto;
}

.top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

h1 {
    color: #8b0000;
}

.btn {
    display: inline-block;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 6px;
    color: white;
    background: #8b0000;
    font-weight: bold;
}

.table-box {
    background: white;
    padding: 15px;
    border-radius: 10px;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th {
    background: #eee;
}

.edit {
    color: #0d6efd;
    text-decoration: none;
    font-weight: bold;
}

.delete {
    color: #dc3545;
    text-decoration: none;
    font-weight: bold;
}

</style>

</head>

<body>

<div class="container">

<div class="top">

<h1>STUDENT MANAGEMENT</h1>

<div>

<a
href="admin_dashboard.php"
class="btn"
>
Dashboard
</a>

<a
href="add_student.php"
class="btn"
>
Add Student
</a>

</div>

</div>


<div class="table-box">

<table>

<tr>

<th>Roll No</th>
<th>Student Name</th>
<th>Class</th>
<th>Action</th>

</tr>

<?php while ($student = $result->fetch_assoc()): ?>

<tr>

<td>
<?= htmlspecialchars(
    $student["roll_no"]
) ?>
</td>

<td>
<?= htmlspecialchars(
    $student["name"]
) ?>
</td>

<td>
    FY - MCA
</td>

<td>

<a
href="edit_student.php?id=<?= $student["id"] ?>"
class="edit"
>
Edit
</a>

&nbsp; | &nbsp;

<a
href="delete_student.php?id=<?= $student["id"] ?>"
class="delete"
onclick="return confirm('Delete this student?')"
>
Delete
</a>

</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</body>

</html>