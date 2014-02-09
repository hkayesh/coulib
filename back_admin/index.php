<?php
session_start();
    if (!($_SESSION['Session_token'] == "2013") && !isset($_SESSION['username'])) {
    header('Location: login.php');
}  else {
    header('Location: admin_home.php');
}
?>