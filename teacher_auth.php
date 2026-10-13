<?php

session_start();

if (
    !isset($_SESSION["teacher_logged_in"]) ||
    $_SESSION["teacher_logged_in"] !== true
) {

    header("Location: teacher_login.php");
    exit;
}