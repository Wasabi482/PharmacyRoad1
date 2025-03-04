<?php

@include '../database/config.php';
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    echo "No session found. Please log in.";
}
$sessionId = 'out';
$updateSession = "UPDATE accounts SET status = '$sessionId' WHERE username ='$username'";
mysqli_query($conn, $updateSession);



session_unset();
session_destroy();

header('Location:../pages/index.php');
exit;
