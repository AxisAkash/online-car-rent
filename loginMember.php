<?php
session_start();

$_SESSION["user_id"] = 2;
$_SESSION["name"] = "Member";
$_SESSION["role"] = "member";

header("Location: /online-car-rent/views/blog/blog_page.php");
exit;