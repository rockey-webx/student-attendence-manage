<?php

require_once "admin_auth.php";
require_once "db.php";

$selectedDate = $_GET["date"] ?? "";
$selectedLecture = $_GET["lecture"] ?? "";

$sql = "
    SELECT
        l.id,
        l.lecture_date,
        l.lecture_number,
        l.lecture_type,
        l.subject_name,
        l.teacher_name,
        l.start_time,
        l.end_time,

        COUNT(DISTINCT a.student_id) AS marked_students,

        COUNT(
            DISTINCT CASE
                WHEN a.status = 'Present'
                THEN a.student_id
            END
        ) AS present,

        COUNT(
            DISTINCT CASE
                WHEN a.status = 'Absent'
                THEN a.student_id
            END
        ) AS absent,

        p.pdf_name,
        p.pdf_path

    FROM lectures l

    LEFT JOIN attendance a
        ON l.id = a.lecture_id

    LEFT JOIN attendance_pdfs p
        ON l.id = p.lecture_id

    WHERE 1 = 1
";

$params = [];
$types = "";

if ($selectedDate !== "") {

    $sql .= " AND l.lecture_date = ? ";

    $params[] = $selectedDate;
    $types .= "s";
}

if ($selectedLecture !== "") {

    $sql .= " AND l.lecture_number = ? ";

    $params[] = intval($selectedLecture);
    $types .= "i";
}

$sql .= "
    GROUP BY
        l.id,
        l.lecture_date,
        l.lecture_number,
        l.lecture_type,
        l.subject_name,
        l.teacher_name,
        l.start_time,
        l.end_time,
        p.pdf_name,
        p.pdf_path

    ORDER BY
        l.lecture_date DESC,
        l.lecture_number ASC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Database error: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0"
>

<title>Admin Dashboard</title>

<link
rel="stylesheet"
href="responsive.css"
>

<style>

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f9;
}

.header {
    background: #8b0000;
    color: white;
    padding: 18px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header h1 {
    margin: 0;
    font-size: 23px;
}

.logout {
    background: white;
    color: #8b0000;
    text-decoration: none;
    padding: 9px 15px;
    border-radius: 6px;
    font-weight: bold;
}

.container {
    width: 94%;
    max-width: 1200px;
    margin: 25px auto;
}

.menu {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}

.menu a {
    text-decoration: none;
    background: white;
    color: #333;
    padding: 12px 18px;
    border-radius: 7px;
    box-shadow: 0 2px 7px rgba(0,0,0,0.10);
    font-weight: bold;
}

.menu a:hover {
    background: #8b0000;
    color: white;
}

.filter-box {
    background: white;
    padding: 18px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.10);
}

.filter-form {
    display: flex;
    align-items: end;
    gap: 15px;
}

.field {
    flex: 1;
}

.field label {
    display: block;
    font-weight: bold;
    margin-bottom: 6px;
}

.field input,
.field select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.search-btn {
    padding: 11px 20px;
    border: none;
    background: #8b0000;
    color: white;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
}

.reset-btn {
    padding: 10px 20px;
    background: #eee;
    color: #333;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
}

.table-box {
    background: white;
    padding: 15px;
    border-radius: 10px;
    overflow-x: auto;
    box-shadow: 0 2px 8px rgba(0,0,0,0.10);
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1000px;
}

th,
td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th {
    background: #eeeeee;
}

.pdf-btn {
    display: inline-block;
    text-decoration: none;
    background: #198754;
    color: white;
    padding: 8px 12px;
    border-radius: 5px;
    font-weight: bold;
    white-space: nowrap;
}

.no-pdf {
    color: #888;
}

@media (max-width: 600px) {

    .filter-form {
        flex-direction: column;
        align-items: stretch;
    }

    .search-btn,
    .reset-btn {
        width: 100%;
        text-align: center;
    }

    .menu {
        flex-direction: column;
    }

    .menu a {
        text-align: center;
    }
}

</style>

</head>

<body>

<div class="header">

<h1>ADMIN DASHBOARD</h1>

<a
href="admin_logout.php"
class="logout"
>
LOGOUT
</a>

</div>


<div class="container">


<div class="menu">

<a href="admin_dashboard.php">
Attendance
</a>

<a href="admin_students.php">
Student List
</a>

<a href="add_student.php">
Add Student
</a>

<a href="add_teacher.php">
Add Teacher
</a>
</div>


<div class="filter-box">

<form
method="GET"
class="filter-form"
>

<div class="field">

<label>Date</label>

<input
type="date"
name="date"
value="<?= htmlspecialchars($selectedDate) ?>"
>

</div>


<div class="field">

<label>Lecture</label>

<select name="lecture">

<option value="">
All Lectures
</option>

<option
value="1"
<?= $selectedLecture === "1" ? "selected" : "" ?>
>
Lecture 1
</option>

<option
value="2"
<?= $selectedLecture === "2" ? "selected" : "" ?>
>
Lecture 2
</option>

</select>

</div>


<button
type="submit"
class="search-btn"
>
SEARCH
</button>


<a
href="admin_dashboard.php"
class="reset-btn"
>
RESET
</a>

</form>

</div>


<div class="table-box">

<table>

<thead>

<tr>

<th>Date</th>
<th>Lecture</th>
<th>Type</th>
<th>Subject</th>
<th>Teacher</th>
<th>Time</th>
<th>Attendance</th>
<th>PDF</th>

</tr>

</thead>

<tbody>

<?php if ($result->num_rows > 0): ?>

<?php while ($row = $result->fetch_assoc()): ?>

<?php

$time =
    date(
        "h:i A",
        strtotime($row["start_time"])
    )
    .
    " - "
    .
    date(
        "h:i A",
        strtotime($row["end_time"])
    );

?>

<tr>

<td>
<?= date(
    "d-m-Y",
    strtotime($row["lecture_date"])
) ?>
</td>

<td>
Lecture
<?= htmlspecialchars(
    $row["lecture_number"]
) ?>
</td>

<td>
<?= htmlspecialchars(
    $row["lecture_type"]
) ?>
</td>

<td>
<?= htmlspecialchars(
    $row["subject_name"]
) ?>
</td>

<td>
<?= htmlspecialchars(
    $row["teacher_name"]
) ?>
</td>

<td>
<?= htmlspecialchars($time) ?>
</td>

<td>

<?= htmlspecialchars(
    $row["marked_students"]
) ?>/60

<br>

Present:
<?= htmlspecialchars(
    $row["present"]
) ?>

<br>

Absent:
<?= htmlspecialchars(
    $row["absent"]
) ?>

</td>

<td>

<?php if (!empty($row["pdf_path"])): ?>

<a
href="admin_download_pdf.php?id=<?= $row["id"] ?>"
class="pdf-btn"
>
Download PDF
</a>

<?php else: ?>

<span class="no-pdf">
Not Generated
</span>

<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="8">
No attendance records found.
</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</body>

</html>