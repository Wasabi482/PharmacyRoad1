<?php
if (isset($_POST['date_transacted']) && isset($_POST['time_transacted'])) {
    $date_transacted = $_POST['date_transacted'];
    $time_transacted = $_POST['time_transacted'];

    $file_path = '../transactions/';
    $file_name = $date_transacted . '.' . str_replace(':', '-', $time_transacted) . '.txt';
    $full_path = $file_path . $file_name;
    $print_command = "print /D:LPT1 " . escapeshellarg($full_path); // Adjust the printer name as necessary
    $print_output = null;
    $print_status = null;
    exec($print_command, $print_output, $print_status);
    if ($print_status === 0) {
        echo "Reprint successful";
    } else {
        echo "Reprint unsuccessful";
    }

    // echo "Reprint successful for transaction on $date_transacted at $time_transacted.";
} else {
    echo "Invalid data.";
}
