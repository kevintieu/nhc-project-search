<?php
    session_start();

    if (!(isset($_SESSION['login']) AND $_SESSION['login'] != '')) {
        // exit program and return to home page
        header("Location: indexnew.php");
	}
?>