<?php

include '../../database/config.php';

session_start();

if (!isset($_SESSION['user_name'])) {
    header('location:../index.php');
    exit();
}

if ($_SESSION['role_as'] == '2'){
    header('location:user_frontend.php');
}elseif ($_SESSION['role_as']== '3'){
    header('location:user_rdu.php');
}
else{

}
?>

