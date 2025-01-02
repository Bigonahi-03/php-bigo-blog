<?php
// Log out of the admin account
session_start();
session_unset();
session_destroy();

header("location:./login.php");
exit;