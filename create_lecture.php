<?php

require_once "db.php";

header(
    "Content-Type: application/json; charset=UTF-8"
);

try {

    if (
        $_SERVER["REQUEST_METHOD"] !== "POST"
    ) {

        throw new Exception(
            "Invalid request."
        );

    }


    /* GET DATA */

    $date =
        $_POST["date"] ?? "";

    $lecture_number =
        intval(
            $_POST["lecture_number"] ?? 0
        );

    $lecture_type =
        $_POST["lecture_type"] ?? "";

    $subject_name =
        trim(
            $_POST["subject_name"] ?? ""
        );

    $teacher_name =
        trim(
            $_POST["teacher_name"] ?? ""
        );

    $start_time =
        $_POST["start_time"] ?? "";

    $end_time =
        $_POST["end_time"] ?? "";


    /* VALIDATION */

    if (
        $date === "" ||
        $lecture_number <= 0 ||
        $lecture_type === "" ||
        $subject_name === "" ||
        $teacher_name === "" ||
        $start_time === "" ||
        $end_time === ""
    ) {

        throw new Exception(
            "Please fill all lecture details."
        );

    }


    /* VALID LECTURE TYPE */

    if (
        $lecture_type !== "Practical" &&
        $lecture_type !== "Theory"
    ) {

        throw new Exception(
            "Invalid lecture type."
        );

    }


    /* VALID LECTURE NUMBER */

    if (
        $lecture_number !== 1 &&
        $lecture_number !== 2
    ) {

        throw new Exception(
            "Invalid lecture number."
        );

    }


    /* VALID AUTOMATIC TIME */

    if (
        $lecture_number === 1
    ) {

        $start_time = "10:00:00";

        $end_time = "12:00:00";

    }


    if (
        $lecture_number === 2
    ) {

        $start_time = "13:00:00";

        $end_time = "16:00:00";

    }


    /* INSERT LECTURE */

    $stmt =
        $conn->prepare("
            INSERT INTO lectures
            (
                lecture_date,
                lecture_number,
                lecture_type,
                subject_name,
                teacher_name,
                start_time,
                end_time
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");


    if (!$stmt) {

        throw new Exception(
            "Database error: " .
            $conn->error
        );

    }


    $stmt->bind_param(
        "sisssss",
        $date,
        $lecture_number,
        $lecture_type,
        $subject_name,
        $teacher_name,
        $start_time,
        $end_time
    );


    /* EXECUTE */

    if (
        !$stmt->execute()
    ) {

        throw new Exception(
            "Database error: " .
            $stmt->error
        );

    }


    /* SUCCESS */

    echo json_encode([

        "success" => true,

        "lecture_id" =>
            $stmt->insert_id,

        "message" =>
            "Lecture started successfully."

    ]);


}

catch (Throwable $e) {

    echo json_encode([

        "success" => false,

        "message" =>
            $e->getMessage()

    ]);

}

?>