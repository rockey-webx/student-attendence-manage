<?php
require_once "admin_auth.php";
require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $teacher_name = trim($_POST["teacher_name"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($teacher_name === "" || $username === "" || $password === "") {

        $message = "Please fill in all fields.";

    } elseif (strlen($password) < 8) {

        $message = "Password must contain at least 8 characters.";

    } else {

        // Check whether username already exists
        $check = $conn->prepare(
            "SELECT id FROM teacher_accounts WHERE username = ?"
        );

        $check->bind_param("s", $username);
        $check->execute();

        $existing = $check->get_result();

        if ($existing->num_rows > 0) {

            $message = "This username already exists. Choose another.";

        } else {

            // Securely hash the password
            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare(
                "INSERT INTO teacher_accounts
(teacher_name, username, password_hash)
VALUES (?, ?, ?)"
            );

            $stmt->bind_param(
                "sss",
                $teacher_name,
                $username,
                $password_hash
            );

            if ($stmt->execute()) {

                $message = "Teacher added successfully!";

            } else {

                $message = "Error adding teacher.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Teacher</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 450px;
            margin: 40px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px #ddd;
        }

        h2 {
            text-align: center;
            color: #8b0000;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: #8b0000;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .message {
            margin-top: 15px;
            text-align: center;
            font-weight: bold;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #8b0000;
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

        <h2>Add New Teacher</h2>

        <?php if ($message !== ""): ?>
            <p class="message">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <form method="POST">

            <label>Teacher Name</label>
            <input type="text" name="teacher_name" required>

            <label>Username</label>
            <input type="text" name="username" required autocomplete="username">

            <label>Password</label>
            <input type="password" name="password" required minlength="8" autocomplete="new-password">

            <button type="submit">Add Teacher</button>

        </form>

        <div class="back-home">
            <a href="admin_dashboard.php">
                Dashboard
            </a>
        </div>

</body>

</html>