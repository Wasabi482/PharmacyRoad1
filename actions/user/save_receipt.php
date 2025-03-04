<?php
include '../../database/config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Manila');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Receipt</title>
    <link rel="icon" href="../../img/icon copy.png" type="image/x-icon" />

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Fetching the variables passed via URL
        if (
            isset($_GET['mode_of_payment']) && isset($_GET['amount_tendered']) && isset($_GET['items']) &&
            isset($_GET['total']) && isset($_GET['change']) && isset($_GET['curr_user']) &&
            isset($_GET['date_transact']) && isset($_GET['time_transacted'])
        ) {

            $mode_of_payment = $_GET['mode_of_payment'];
            $amount_tendered = $_GET['amount_tendered'];
            $items = json_decode($_GET['items'], true);
            $total = $_GET['total'];
            $change = $_GET['change'];
            $curr_user = $_GET['curr_user'];
            $date_transact = $_GET['date_transact'];
            $time_transacted = $_GET['time_transacted'];

            // Set establishment details
            $establishment_name = "Road 1 Pharmacy";
            $proprietor = "Ronaldo Borbon";
            $address = "Unit 2 Ipo road, Minuyan, Sjdm, Bulacan";
            $contact_number = "Contact Number";
            $validity = "Receipt is valid for 30 days.";

            // Create the receipt text
            $receipt = "-----------------------------------------\n";
            $receipt .= $establishment_name . "\n";
            $receipt .= "Proprietor: " . $proprietor . "\n";
            $receipt .= $address . "\n";
            $receipt .= "Contact: " . $contact_number . "\n";
            $receipt .= "-----------------------------------------\n";
            $receipt .= "Transaction Date: " . $date_transact . "\n";
            $receipt .= "Transaction Time: " . $time_transacted . "\n";
            $receipt .= "-----------------------------------------\n";
            $receipt .= "Transacted By: " . $curr_user . "\n";
            $receipt .= "-----------------------------------------\n";
            $receipt .= "Items:\n";
            foreach ($items as $item) {
                $receipt .= $item['item_name'] . " - " . $item['quantity'] . " x " . $item['price'] . "\n";
            }
            $receipt .= "-----------------------------------------\n";
            $receipt .= "Mode of Payment: " . $mode_of_payment . "\n";
            $receipt .= "Amount Tendered: " . $amount_tendered . "\n";
            $receipt .= "-----------------------------------------\n";
            $receipt .= "Total: " . $total . "\n";
            $receipt .= "Change: " . $change . "\n";
            $receipt .= "-----------------------------------------\n";
            $receipt .= $validity . "\n";
            $receipt .= "-----------------------------------------\n";
            $receipt .= "-----------------------------------------\n";
            $receipt .= "-----------------------------------------\n";
            // Define the file path and name
            $file_path = '../transactions/';
            $file_name = $date_transact . '.' . str_replace(':', '-', $time_transacted) . '.txt';
            $full_path = $file_path . $file_name;

            // Save the receipt to a file
            if (file_put_contents($full_path, $receipt) !== false) {
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>";
                echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Receipt saved successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../../pages/user/user_frontend.php';
                    }
                });
                </script>";
            } else {
                echo "Error saving receipt.";
            }
        } else {
            echo "Missing required fields.";
        }
    } else {
        echo "Invalid request method.";
    }
    ?>
</body>

</html>