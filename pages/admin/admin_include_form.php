<?php
include '../../database/config.php';


// Check if the 'form' GET parameter is set
if (isset($_GET['form'])) {
    $form = $_GET['form'];

    // Include the appropriate form based on the selection
    if ($form == 'order') {
        include 'admin_view_push.php'; // Path to your Push Order form file
    } elseif ($form == 'report') {
        include 'admin_view_reports.php'; // Path to your Report Item form file
    } elseif ($form == 'medicine') {
        include 'add_medicine.php';
    } elseif ($form == 'alcohol') {
        include 'add_alcohol.php';
    } elseif ($form == 'creams_ointment') {
        include 'add_cNo.php';
    }
}
