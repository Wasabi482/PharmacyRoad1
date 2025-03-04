<?php
include '../../database/config.php';


// Check if the 'form' GET parameter is set
if (isset($_GET['form'])) {
    $form = $_GET['form'];

    // Include the appropriate form based on the selection
    if ($form == 'order') {
        include 'user_rdu_push.php'; // Path to your Push Order form file
    } elseif ($form == 'report') {
        include 'user_rdu_report.php'; // Path to your Report Item form file
    }
}
?>