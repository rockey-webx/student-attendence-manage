<?php

session_start();

require_once "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim(
        $_POST["username"] ?? ""
    );

    $password =
        $_POST["password"] ?? "";

    if (
        $username === "" ||
        $password === ""
    ) {

        $error = "Please enter username and password.";

    } else {

        $stmt = $conn->prepare("
            SELECT
                id,
                username,
                password_hash
            FROM admins
            WHERE username = ?
            LIMIT 1
        ");

        $stmt->bind_param(
            "s",
            $username
        );

        $stmt->execute();

        $result =
            $stmt->get_result();

        if ($result->num_rows === 1) {

            $admin =
                $result->fetch_assoc();

            if (
                password_verify(
                    $password,
                    $admin["password_hash"]
                )
            ) {

                $_SESSION["admin_logged_in"] = true;

                $_SESSION["admin_id"] =
                    $admin["id"];

                $_SESSION["admin_username"] =
                    $admin["username"];

                header(
                    "Location: admin_dashboard.php"
                );

                exit;

            } else {

                $error =
                    "Invalid username or password.";

            }

        } else {

            $error =
                "Invalid username or password.";
        }
    }
}

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Admin Login</title>
<link rel="stylesheet" href="responsive.css">

<style>

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f9;
}

.login-box {
    width: 90%;
    max-width: 400px;
    margin: 100px auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

h1 {
    text-align: center;
    color: #8b0000;
    margin-bottom: 25px;
}

label {
    display: block;
    font-weight: bold;
    margin-top: 15px;
    margin-bottom: 6px;
}

input {
    width: 100%;
    padding: 12px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
}

button {
    width: 100%;
    margin-top: 25px;
    padding: 13px;
    border: none;
    border-radius: 6px;
    background: #8b0000;
    color: white;
    font-size: 17px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    background: #650000;
}

.error {
    background: #f8d7da;
    color: #842029;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
    text-align: center;
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

<div class="login-box">

<h1>ADMIN LOGIN</h1>

<?php if ($error !== ""): ?>

<div class="error">
    <?= htmlspecialchars($error) ?>
</div>

<?php endif; ?>

<form method="POST">

<label>Username</label>

<input
    type="text"
    name="username"
    required
>

<label>Password</label>

<input
    type="password"
    name="password"
    required
>

<button type="submit">
    LOGIN
</button>

</form>

</div>
<h2 class="back-home">
    <a href="index.php">BACK TO HOME</a>
</h2>

</body>

</html>