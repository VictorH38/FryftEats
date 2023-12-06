<?php
    session_start();

    if (isset($_SESSION)) {
        session_destroy();
        $_SESSION = array();
    }

    header("Location: home.php");
    exit;
?>
