<?php
session_start();

session_unset();
session_destroy();

header("Location: /online-car-rent/index.php");
exit;