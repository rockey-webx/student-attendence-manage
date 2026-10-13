<?php
require_once "teacher_auth.php";
require_once "db.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Start Attendance - FY MCA</title>
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
            max-width: 700px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        }

        h1 {
            text-align: center;
            color: #8b0000;
            margin-bottom: 5px;
        }

        .class-name {
            text-align: center;
            font-size: 19px;
            font-weight: bold;
            color: #555;
            margin-bottom: 25px;
        }

        .lecture-box {
            background: #f8f8f8;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        .lecture-box h2 {
            margin-top: 0;
            color: #8b0000;
            font-size: 20px;
        }

        label {
            display: block;
            margin-top: 12px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        select,
        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        input[readonly] {
            background: #e9ecef;
        }

        .start-btn {
            width: 100%;
            margin-top: 20px;
            padding: 13px;
            border: none;
            border-radius: 6px;
            background: #8b0000;
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
        }

        .start-btn:hover {
            background: #650000;
        }

        .start-btn:disabled {
            background: #777;
            cursor: not-allowed;
        }

        .attendance-box {
            margin-top: 25px;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
        }

        .attendance-box h2 {
            color: #8b0000;
        }

        .roll-input {
            font-size: 20px;
            text-align: center;
        }

        .student-name {
            margin-top: 15px;
            padding: 15px;
            background: #f1f1f1;
            border-radius: 7px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }

        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .present,
        .absent {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 7px;
            color: white;
            font-size: 17px;
            font-weight: bold;
            cursor: pointer;
        }

        .present {
            background: #198754;
        }

        .absent {
            background: #dc3545;
        }

        .present:hover {
            background: #146c43;
        }

        .absent:hover {
            background: #bb2d3b;
        }

        .status {
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }

        .counter {
            margin-top: 20px;
            text-align: center;
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            font-size: 20px;
            font-weight: bold;
        }

        .locked {
            background: #e9ecef !important;
        }

        .lecture-info {
            margin-top: 15px;
            padding: 12px;
            background: #e8f5e9;
            border-radius: 7px;
            font-weight: bold;
            display: none;
        }

        .pdf-message {
            margin-top: 20px;
            padding: 15px;
            background: #d1e7dd;
            color: #0f5132;
            border-radius: 7px;
            text-align: center;
            font-weight: bold;
            display: none;
        }

        .top-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-bottom: 10px;
}

.top-buttons a {
    text-decoration: none;
    padding: 9px 15px;
    border-radius: 6px;
    color: white;
    font-weight: bold;
    font-size: 14px;
    margin-top: 10px;
    margin-right: 50px
}

.admin-btn {
    background: #0d6efd;
}

.logout-btn {
    background: #dc3545;
}

.top-buttons a:hover {
    opacity: 5.0;
}
    </style>

</head>

<body>
    <div class="top-buttons">

    <a href="admin_login.php" class="admin-btn">
        ADMIN
    </a>

    <a href="teacher_logout.php" class="logout-btn">
        LOGOUT
    </a>

</div>

    <div class="container">

        <h1>START ATTENDANCE</h1>

        <div class="class-name">
            Class: FY - MCA
        </div>


        <!-- ==============================
         LECTURE DETAILS
    =============================== -->

        <div class="lecture-box">

            <h2>Lecture Details</h2>


            <!-- DATE -->

            <label>Date</label>

            <input type="date" id="date" readonly>


            <!-- LECTURE NUMBER -->

            <label>Lecture Number</label>

            <select id="lecture_number" onchange="setLectureTime()">

                <option value="">
                    Select Lecture
                </option>

                <option value="1">
                    Lecture 1
                </option>

                <option value="2">
                    Lecture 2
                </option>



            </select>


            <!-- LECTURE TYPE -->

            <label>Lecture Type</label>

            <select id="lecture_type">

                <option value="">
                    Select Type
                </option>

                <option value="Practical">
                    Practical
                </option>

                <option value="Theory">
                    Theory
                </option>

            </select>


            <!-- SUBJECT -->

            <label>Subject</label>

            <select id="subject">

                <option value="">
                    Select Subject
                </option>

                <option value="Adv.DBMS">
                    Adv.DBMS
                </option>

                <option value="Mathematical CS">
                    Mathematical CS
                </option>

                <option value="Adv.Java">
                    Adv.Java
                </option>

                <option value="S.Project Management">
                    S.Project Management
                </option>

                <option value="Digital marketing and BA">
                    Digital marketing and BA
                </option>

                <option value="Adv.DS">
                    Adv.DS
                </option>

                <option value="Web Technologies">
                    Web Technologies
                </option>

            </select>


            <!-- TEACHER -->

            <label>Teacher</label>

            <select id="teacher">

                <option value="">
                    Select Teacher
                </option>

                <option value="Sandhya Thakkar">
                    Sandhya Thakkar
                </option>

                <option value="Sandeep Vishwakarma">
                    Sandeep Vishwakarma
                </option>

            </select>


            <!-- AUTOMATIC LECTURE TIME -->

            <label>Lecture Time</label>

            <input type="text" id="lecture_time" placeholder="Select Lecture Number" readonly>


            <!-- START BUTTON -->

            <button class="start-btn" onclick="startAttendance()" id="startButton">
                START ATTENDANCE
            </button>


            <div class="lecture-info" id="lectureInfo">
                Attendance Started
            </div>

        </div>


        <!-- ==============================
         ATTENDANCE
    =============================== -->

        <div class="attendance-box" id="attendanceSection" style="display:none;">

            <h2>Student Attendance</h2>


            <!-- COUNTER -->

            <div class="counter" id="counter">
                Attendance: 0 / 60
            </div>


            <!-- ROLL NUMBER -->

            <label>
                Roll Number
            </label>

            <input type="number" id="roll_no" class="roll-input" placeholder="Roll Number" readonly>


            <!-- STUDENT NAME -->

            <div class="student-name" id="studentName">
                Enter Roll Number → Student Name will appear here
            </div>


            <!-- BUTTONS -->

            <div class="buttons">

                <button class="present" onclick="markAttendance('Present')">
                    PRESENT
                </button>

                <button class="absent" onclick="markAttendance('Absent')">
                    ABSENT
                </button>

            </div>


            <!-- STATUS -->

            <div class="status" id="status">

            </div>


            <!-- PDF MESSAGE -->



            <div class="pdf-message" id="pdfMessage">

                🎉 60/60 Attendance Completed!

                <br><br>

                Generating Attendance PDF...

            </div>

        </div>

    </div>


    <script>


        /* =====================================
           VARIABLES
        ===================================== */

        let lectureId = 0;

        let currentStudentId = 0;

        let attendanceCount = 0;

        let startTime = "";

        let endTime = "";


        /* =====================================
           SET CURRENT DATE
        ===================================== */

        window.onload = function () {

            let today = new Date();

            let year =
                today.getFullYear();

            let month =
                String(
                    today.getMonth() + 1
                ).padStart(2, "0");

            let day =
                String(
                    today.getDate()
                ).padStart(2, "0");

            document.getElementById(
                "date"
            ).value =
                year + "-" + month + "-" + day;
        };


        /* =====================================
           AUTOMATIC LECTURE TIME
        ===================================== */

        function setLectureTime() {

            let lecture =
                document.getElementById(
                    "lecture_number"
                ).value;


            let lectureTime =
                document.getElementById(
                    "lecture_time"
                );


            if (lecture === "1") {

                lectureTime.value =
                    "10:30 AM - 12:30 PM";

                startTime = "10:30";

                endTime = "12:30";

            }



            else if (lecture === "2") {

                lectureTime.value =
                    "01:30 PM - 04:30 PM";

                startTime = "1:30";

                endTime = "4:30";

            }

            else {

                lectureTime.value = "";

                startTime = "";

                endTime = "";

            }

        }


        /* =====================================
           START ATTENDANCE
        ===================================== */

        function startAttendance() {


            let date =
                document.getElementById(
                    "date"
                ).value;


            let lecture =
                document.getElementById(
                    "lecture_number"
                ).value;


            let type =
                document.getElementById(
                    "lecture_type"
                ).value;


            let subject =
                document.getElementById(
                    "subject"
                ).value;


            let teacher =
                document.getElementById(
                    "teacher"
                ).value;


            /* VALIDATION */

            if (
                date === "" ||
                lecture === "" ||
                type === "" ||
                subject === "" ||
                teacher === "" ||
                startTime === "" ||
                endTime === ""
            ) {

                alert(
                    "Please fill all lecture details."
                );

                return;
            }


            /* FORM DATA */

            let formData =
                new FormData();


            formData.append(
                "date",
                date
            );


            formData.append(
                "lecture_number",
                lecture
            );


            formData.append(
                "lecture_type",
                type
            );


            /* SUBJECT NAME */

            formData.append(
                "subject_name",
                subject
            );


            /* TEACHER NAME */

            formData.append(
                "teacher_name",
                teacher
            );


            /* AUTOMATIC TIME */

            formData.append(
                "start_time",
                startTime
            );


            formData.append(
                "end_time",
                endTime
            );


            /* SEND */

            fetch(
                "create_lecture.php",
                {
                    method: "POST",
                    body: formData
                }
            )

                .then(
                    response =>
                        response.json()
                )

                .then(
                    data => {


                        if (!data.success) {

                            alert(
                                data.message
                            );

                            return;
                        }


                        /* SAVE LECTURE ID */

                        lectureId =
                            data.lecture_id;


                        /* LOCK FIELDS */

                        document
                            .querySelectorAll(
                                "#date, #lecture_number, #lecture_type, #subject, #teacher, #lecture_time"
                            )
                            .forEach(
                                element => {

                                    element.disabled = true;

                                    element.classList.add(
                                        "locked"
                                    );

                                }
                            );


                        /* DISABLE BUTTON */

                        document.getElementById(
                            "startButton"
                        ).disabled = true;


                        document.getElementById(
                            "startButton"
                        ).innerText =
                            "ATTENDANCE RUNNING";


                        /* MESSAGE */

                        document.getElementById(
                            "lectureInfo"
                        ).style.display =
                            "block";


                        document.getElementById(
                            "lectureInfo"
                        ).innerHTML =
                            "✓ Lecture Started Successfully";


                        /* SHOW ATTENDANCE */
                        document.getElementById(
                            "attendanceSection"
                        ).style.display = "block";


                        // Automatically show Roll No. 1
                        document.getElementById("roll_no").value = 1;

                        findStudent();


                        document.getElementById(
                            "roll_no"
                        ).focus();


                        alert(
                            "Attendance Started Successfully!"
                        );


                    }
                )

                .catch(
                    error => {

                        console.error(error);

                        alert(
                            "Something went wrong while starting attendance."
                        );

                    }
                );

        }


        /* =====================================
           FIND STUDENT
        ===================================== */
        /* =========================
           FIND STUDENT
        ========================= */

        function findStudent() {

            let roll =
                document.getElementById("roll_no").value;

            currentStudentId = 0;

            if (roll === "") {

                document.getElementById("studentName").innerHTML =
                    "Student Name will appear here";

                return;
            }

            fetch(
                "get_student.php?roll_no=" +
                encodeURIComponent(roll)
            )

                .then(response => response.json())

                .then(data => {

                    if (!data.success) {

                        currentStudentId = 0;

                        document.getElementById("studentName").innerHTML =
                            "Student Not Found";

                    } else {

                        currentStudentId = data.id;

                        document.getElementById("studentName").innerHTML =
                            "Roll No: " +
                            data.roll_no +
                            " → " +
                            data.name;
                    }

                })

                .catch(error => {

                    console.error(error);

                    document.getElementById("studentName").innerHTML =
                        "Error loading student";

                });
        }


        /* =========================
           MARK ATTENDANCE
        ========================= */

        function markAttendance(status) {

            let roll =
                document.getElementById("roll_no").value;

            let name =
                document.getElementById("studentName").innerText;

            name = name.replace(
                /^Roll No:\s*\d+\s*→\s*/,
                ""
            );





            if (
                lectureId === 0 ||
                currentStudentId === 0 ||
                roll === "" ||
                name === "Student Not Found"
            ) {

                alert("Student not found.");

                return;
            }


            let formData = new FormData();

            formData.append(
                "lecture_id",
                lectureId
            );

            formData.append(
                "student_id",
                currentStudentId
            );

            formData.append(
                "status",
                status
            );


            fetch("save_attendance.php", {

                method: "POST",

                body: formData

            })

                .then(response => response.json())

                .then(data => {

                    if (!data.success) {

                        alert(data.message);

                        return;
                    }


                    /* Increase attendance counter */

                    attendanceCount++;


                    document.getElementById("counter").innerHTML =
                        "Attendance: " +
                        attendanceCount +
                        " / 60";


                    document.getElementById("status").innerHTML =
                        "Roll No " +
                        roll +
                        " → " +
                        name +
                        " → " +
                        status +
                        " ✓ Saved";


                    /*
                     * Automatically go to NEXT Roll Number
                     */

                    let nextRoll =
                        parseInt(roll) + 1;


                    /*
                     * Clear old student
                     */

                    currentStudentId = 0;


                    /*
                     * Put next roll number automatically
                     */

                    document.getElementById("roll_no").value =
                        nextRoll;


                    /*
                     * Automatically load next student's name
                     */

                    findStudent();


                    /*
                     * Stop after 60 students
                     */
                    if (attendanceCount >= 60) {

                        document.getElementById("pdfMessage").style.display =
                            "block";

                        document.getElementById("roll_no").value = "";

                        document.getElementById("studentName").innerHTML =
                            "🎉 Attendance completed for all 60 students!";

                        window.location.href =
                            "generate_attendance_pdf.php";

                        return;
                    }

                })

                .catch(error => {

                    console.error(error);

                    alert("Attendance save failed.");

                });

        }


    </script>

</body>

</html>