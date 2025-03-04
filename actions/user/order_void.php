<?php
session_start(); 

include '../../database/config.php'; 
if (isset($_GET['index'])) {
    $index = filter_input(INPUT_GET, 'index', FILTER_SANITIZE_NUMBER_INT);
    if (isset($_SESSION['items'][$index])) {
        unset($_SESSION['items'][$index]);
        $_SESSION['items'] = array_values($_SESSION['items']);
    }
} else {
    echo "Index not set.";
}

// Redirect back to the previous page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>