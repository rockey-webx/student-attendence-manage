<?php

session_start();

$_SESSION = [];

session_destroy();

header("Location: teacher_login.php");

exit;