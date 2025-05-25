<?php
// logout.php - Logout Script
session_start();
session_destroy();
header("Location: ThinkBoxLoginPage.php");
exit();
?>
