<?php
session_start();
require_once "db.php";

// Only allow a currently logged-in admin
if (empty($_SESSION["admin_logged_in"])) {
    http_response_code(403);
    exit("Please log in as admin first.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newPassword = $_POST["new_password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    if (strlen($newPassword) < 12) {
        $message = "Use at least 12 characters.";
    } elseif ($newPassword !== $confirmPassword) {
        $message = "Passwords do not match.";
    } else {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $adminId = (int) $_SESSION["admin_id"];

        $stmt = $conn->prepare(
            "UPDATE admins SET password_hash = ? WHERE id = ?"
        );
        $stmt->bind_param("si", $hash, $adminId);

        if ($stmt->execute() && $stmt->affected_rows === 1) {
            $message = "Password changed successfully.";
        } else {
            $message = "Password not changed. Please check the admin account.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Change Admin Password</title>
</head>
<body>
<h2>Change Admin Password</h2>

<?php if ($message !== ""): ?>
<p><?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?></p>
<?php endif; ?>

<form method="post">
    <label>New password</label><br>
    <input type="password" name="new_password"
           minlength="12" autocomplete="new-password" required><br><br>

    <label>Confirm new password</label><br>
    <input type="password" name="confirm_password"
           minlength="12" autocomplete="new-password" required><br><br>

    <button type="submit">Change Password</button>
</form>
</body>
</html>