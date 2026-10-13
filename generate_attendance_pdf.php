<?php

require_once "db.php";
require_once "vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;


/* =========================================
   GET LATEST LECTURE AUTOMATICALLY
========================================= */

$stmt = $conn->prepare("
    SELECT
        id,
        lecture_date,
        lecture_number,
        lecture_type,
        subject_name,
        teacher_name,
        start_time,
        end_time
    FROM lectures
    WHERE lecture_date = CURDATE()
    ORDER BY id DESC
    LIMIT 1
");

if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Today's lecture not found.");
}

$lecture = $result->fetch_assoc();

$lecture_id = intval($lecture["id"]);


/* =========================================
   GET STUDENTS + ATTENDANCE
========================================= */

$stmt = $conn->prepare("
    SELECT
        s.roll_no,
        s.name,
        a.status
    FROM students s

    LEFT JOIN attendance a
        ON s.id = a.student_id
        AND a.lecture_id = ?

    ORDER BY s.roll_no ASC
");

if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param("i", $lecture_id);
$stmt->execute();

$result = $stmt->get_result();


$students = [];

$totalStudents = 0;
$present = 0;
$absent = 0;


while ($row = $result->fetch_assoc()) {

    $students[] = $row;

    $totalStudents++;

    if ($row["status"] === "Present") {
        $present++;
    }

    if ($row["status"] === "Absent") {
        $absent++;
    }
}


/* =========================================
   CHECK 60 STUDENTS
========================================= */

if ($totalStudents != 60) {

    die(
        "Student total is " .
        $totalStudents .
        ". System requires 60 students."
    );
}


/* =========================================
   CHECK 60 ATTENDANCE RECORDS
========================================= */

$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT student_id) AS total
    FROM attendance
    WHERE lecture_id = ?
");

if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param("i", $lecture_id);
$stmt->execute();

$countResult = $stmt->get_result();
$countData = $countResult->fetch_assoc();

$markedStudents = intval($countData["total"]);


if ($markedStudents < 60) {

    die(
        "Attendance is not completed. " .
        $markedStudents .
        "/60 students marked."
    );
}


/* =========================================
   DOMPDF OPTIONS
========================================= */

$options = new Options();

$options->set("isRemoteEnabled", true);

$dompdf = new Dompdf($options);


/* =========================================
   DATE + TIME
========================================= */

$pdfDate = date(
    "d-m-Y",
    strtotime($lecture["lecture_date"])
);

$pdfStartTime = date(
    "h:i A",
    strtotime($lecture["start_time"])
);

$pdfEndTime = date(
    "h:i A",
    strtotime($lecture["end_time"])
);


/* =========================================
   SAFE VALUES
========================================= */

$lectureNumber = htmlspecialchars(
    (string)$lecture["lecture_number"],
    ENT_QUOTES,
    "UTF-8"
);

$lectureType = htmlspecialchars(
    (string)$lecture["lecture_type"],
    ENT_QUOTES,
    "UTF-8"
);

$subjectName = htmlspecialchars(
    (string)$lecture["subject_name"],
    ENT_QUOTES,
    "UTF-8"
);

$teacherName = htmlspecialchars(
    (string)$lecture["teacher_name"],
    ENT_QUOTES,
    "UTF-8"
);

$timeText =
    htmlspecialchars(
        $pdfStartTime,
        ENT_QUOTES,
        "UTF-8"
    )
    .
    " - "
    .
    htmlspecialchars(
        $pdfEndTime,
        ENT_QUOTES,
        "UTF-8"
    );


/* =========================================
   HTML
========================================= */

$html = <<<HTML

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<style>

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 11px;
    margin: 25px;
}

.header {
    text-align: center;
}

.logo {
    width: 70px;
    margin-bottom: 8px;
}

.college-name {
    font-size: 18px;
    font-weight: bold;
}

.affiliation {
    font-size: 11px;
    margin-top: 4px;
}

.naac {
    font-size: 11px;
    margin-top: 3px;
}

.line {
    border-top: 1px solid #000;
    margin-top: 12px;
    margin-bottom: 12px;
}


/* =========================================
   LECTURE DETAILS
========================================= */

.details {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    margin-bottom: 15px;
}

.details td {
    padding: 6px 7px;
    font-size: 11px;
    vertical-align: middle;
}

.details strong {
    font-weight: bold;
}


/* =========================================
   ATTENDANCE TABLE
========================================= */

.attendance {
    width: 100%;
    border-collapse: collapse;
}

.attendance th {
    background: #eeeeee;
    border: 1px solid #000;
    padding: 7px;
    text-align: center;
}

.attendance td {
    border: 1px solid #000;
    padding: 6px;
}

.roll {
    width: 15%;
    text-align: center;
}

.student {
    width: 60%;
}

.status {
    width: 25%;
    text-align: center;
}


/* =========================================
   SUMMARY
========================================= */

.summary {
    margin-top: 15px;
    font-weight: bold;
    line-height: 1.7;
}


/* =========================================
   SIGNATURE
========================================= */

.signature {
    margin-top: 40px;
    text-align: right;
}

</style>

</head>

<body>


<!-- =====================================
     HEADER
===================================== -->

<div class="header">

    <img
        src="https://saketcollege.edu.in/static/logo2.png"
        class="logo"
    >

    <div class="college-name">
        SAKET INSTITUDE OF MANAGEMENT
    </div>

    <div class="affiliation">
        (Permanently Affiliated To University Of Mumbai)
    </div>

    <div class="naac">
        NAAC Re-Accredited 'B' Grade
    </div>

</div>


<div class="line"></div>


<!-- =====================================
     LECTURE DETAILS
===================================== -->

<table class="details">

    <tr>

        <td colspan="3">
            <strong>Class:</strong> FY - MCA
        </td>

        <td colspan="3">
            <strong>Date:</strong> {$pdfDate}
        </td>

    </tr>


    <tr>

        <td colspan="2">
            <strong>Lecture No:</strong> {$lectureNumber}
        </td>

        <td colspan="2">
            <strong>Lecture Type:</strong> {$lectureType}
        </td>

        <td colspan="2">
            <strong>Subject:</strong> {$subjectName}
        </td>

    </tr>


    <tr>

        <td colspan="3">
            <strong>Teacher:</strong> {$teacherName}
        </td>

        <td colspan="3">
            <strong>Time:</strong> {$timeText}
        </td>

    </tr>

</table>


<!-- =====================================
     ATTENDANCE TABLE
===================================== -->

<table class="attendance">

    <tr>

        <th class="roll">
            Roll No
        </th>

        <th class="student">
            Student Name
        </th>

        <th class="status">
            Status
        </th>

    </tr>

HTML;


/* =========================================
   ADD STUDENTS
========================================= */

foreach ($students as $student) {

    $rollNo = htmlspecialchars(
        (string)$student["roll_no"],
        ENT_QUOTES,
        "UTF-8"
    );

    $studentName = htmlspecialchars(
        (string)$student["name"],
        ENT_QUOTES,
        "UTF-8"
    );

    $studentStatus =
        $student["status"] ?? "Not Marked";

    $studentStatus = htmlspecialchars(
        (string)$studentStatus,
        ENT_QUOTES,
        "UTF-8"
    );


    $html .= "

    <tr>

        <td class=\"roll\">
            {$rollNo}
        </td>

        <td class=\"student\">
            {$studentName}
        </td>

        <td class=\"status\">
            {$studentStatus}
        </td>

    </tr>

    ";
}


/* =========================================
   CLOSE TABLE + SUMMARY
========================================= */

$html .= "

</table>


<div class=\"summary\">

    Total Students: {$totalStudents}<br>

    Present: {$present}<br>

    Absent: {$absent}

</div>


<div class=\"signature\">

    Teacher Signature: ____________________

</div>


</body>

</html>

";


/* =========================================
   LOAD HTML
========================================= */

$dompdf->loadHtml($html);


/* =========================================
   A4 PAPER
========================================= */

$dompdf->setPaper(
    "A4",
    "portrait"
);


/* =========================================
   GENERATE PDF
========================================= */

$dompdf->render();

$pdfOutput = $dompdf->output();


/* =========================================
   CREATE PDF FILE NAME
========================================= */

$safeSubject = preg_replace(
    "/[^A-Za-z0-9_-]/",
    "_",
    $lecture["subject_name"]
);

$fileName =
    date("d-m-Y", strtotime($lecture["lecture_date"])) .
    "_Lecture-" .
    $lecture["lecture_number"] .
    "_" .
    $safeSubject .
    ".pdf";


/* =========================================
   CREATE PDF FOLDER
========================================= */

$pdfFolder = __DIR__ . "/attendance_pdfs/";

if (!is_dir($pdfFolder)) {
    if (!mkdir($pdfFolder, 0775, true) && !is_dir($pdfFolder)) {
        die("Error: Could not create attendance_pdfs folder.");
    }
}


/* =========================================
   SAVE PDF PERMANENTLY
========================================= */

$filePath = $pdfFolder . $fileName;

if (file_put_contents($filePath, $pdfOutput) === false) {
    die("Error: PDF could not be saved. Check folder permissions.");
}

if (!is_file($filePath)) {
    die("Error: PDF file was not created.");
}


/* =========================================
   SAVE PDF PATH IN DATABASE
========================================= */

$pdfPath = "attendance_pdfs/" . $fileName;

$savePdf = $conn->prepare("
    INSERT INTO attendance_pdfs
        (lecture_id, pdf_name, pdf_path)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE
        pdf_name = VALUES(pdf_name),
        pdf_path = VALUES(pdf_path)
");

if (!$savePdf) {
    die("Database error: " . $conn->error);
}

$savePdf->bind_param(
    "iss",
    $lecture_id,
    $fileName,
    $pdfPath
);

if (!$savePdf->execute()) {
    die("PDF saved, but database update failed: " . $savePdf->error);
}


/* =========================================
   DOWNLOAD PDF
========================================= */

if (ob_get_length()) {
    ob_end_clean();
}

header("Content-Type: application/pdf");
header(
    'Content-Disposition: attachment; filename="' .
    basename($fileName) .
    '"'
);
header("Content-Length: " . filesize($filePath));

readfile($filePath);
exit;