<?php

require_once "admin_auth.php";
require_once "db.php";


$lecture_id =
    intval($_GET["id"] ?? 0);


if ($lecture_id <= 0) {

    die("Invalid request.");
}


$stmt = $conn->prepare("
    SELECT
        pdf_name,
        pdf_path
    FROM attendance_pdfs
    WHERE lecture_id = ?
    LIMIT 1
");

$stmt->bind_param(
    "i",
    $lecture_id
);

$stmt->execute();

$result =
    $stmt->get_result();


if ($result->num_rows === 0) {

    die("PDF not found.");
}


$pdf =
    $result->fetch_assoc();


$filePath =
    __DIR__ . "/" . $pdf["pdf_path"];


if (!file_exists($filePath)) {

    die("PDF file does not exist.");
}


header(
    "Content-Type: application/pdf"
);

header(
    "Content-Disposition: attachment; filename=\"" .
    basename($pdf["pdf_name"]) .
    "\""
);

header(
    "Content-Length: " .
    filesize($filePath)
);

readfile($filePath);

exit;

$dompdf->render();

$pdfOutput = $dompdf->output();

$pdfFolder = __DIR__ . "/attendance_pdfs/";

if (!is_dir($pdfFolder)) {
    mkdir($pdfFolder, 0775, true);
}

$filePath = $pdfFolder . $fileName;

if (file_put_contents($filePath, $pdfOutput) === false) {
    die("Error: PDF could not be saved. Check folder permissions.");
}

if (!file_exists($filePath)) {
    die("Error: PDF file does not exist after saving.");
}
?>