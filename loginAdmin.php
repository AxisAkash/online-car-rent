<?php
session_start();

$_SESSION["user_id"] = 1;
$_SESSION["name"] = "Admin";
$_SESSION["role"] = "admin";

header("Location: /online-car-rent/views/blog/blog_page.php");
exit;