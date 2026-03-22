<?php
session_start();
session_destroy();
header("Location:    /campusvibe/pages/login.php");
exit();
?>