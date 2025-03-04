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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    </link>
    <!-- Bootstrap Bundle JS (includes Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Checkouting</title>
    <link rel="icon" href="../../img/icon copy.png" type="image/x-icon" />

</head>

<body>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['items']) && isset($_POST['total'])) {
        if (isset($_POST['mode_of_payment']) && isset($_POST['tender_amount']) && isset($_POST['curr_user'])) {

            $mode_of_payment = $_POST['mode_of_payment'];
            $amount_tendered = $_POST['tender_amount'];
            $items = json_decode($_POST['items'], true);
            $total = $_POST['total'];
            $change = $amount_tendered - $total;
            $curr_user = $_POST['curr_user'];
            $date_transact = date('Y-m-d');
            $time_transacted = date('H:i:s');
            $gen = $_POST['gen'];


            // All items are valid, proceed with the rest of the code
            $insert = "INSERT INTO transactions (amount, tender_amount, gen_sales,date_transacted, time_transacted, payment_mode, transact_by)
                       VALUES ('$total', '$amount_tendered','$gen', '$date_transact','$time_transacted', '$mode_of_payment', '$curr_user')";

            if ($conn->query($insert) === TRUE) {
                $last_id = $conn->insert_id;
                $all_items_inserted = true; // Flag to check if all items are inserted successfully

                foreach ($items as $item) {
                    $item_name = $item['item_name'];
                    $price = $item['price'];
                    $qty = $item['quantity'];

                    $insert2 = "INSERT INTO transactions_items(order_id, item_name, price, qty)
                                VALUES ('$last_id', '$item_name', '$price', '$qty')";
                    if ($conn->query($insert2) !== TRUE) {
                        $all_items_inserted = false;
                        echo "Error: " . $insert2 . "<br>" . $conn->error;
                        break; // Exit the loop if an error occurs
                    }
                }

                if ($all_items_inserted) {


                    unset($_SESSION['items']);
                    $encoded_items = json_encode($items);
                    echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Transaction completed successfully. Do you want to print the receipt?',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: '<span class=\"print-btn\">Print</span>',
                    cancelButtonText: '<span class=\"save-btn\">Just Save</span>',
                    customClass: {
                        confirmButton: 'btn-green',
                        cancelButton: 'btn-black'
                    }
                }).then((result) => {
                    let urlParams = new URLSearchParams({
                        mode_of_payment: '$mode_of_payment',
                        amount_tendered: '$amount_tendered',
                        items: '$encoded_items',
                        total: '$total',
                        change: '$change',
                        curr_user: '$curr_user',
                        date_transact: '$date_transact',
                        time_transacted: '$time_transacted'
                    });
                    if (result.isConfirmed) {
                        window.location.href = 'print_receipt.php?' + urlParams.toString();
                    } else {
                        window.location.href = 'save_receipt.php?' + urlParams.toString();
                    }
                });
                </script>";
                }
            } else {
                echo "Error: " . $insert . "<br>" . $conn->error;
            }
        } else {
            echo "Missing required fields.";
        }
    }
    ?>

</body>

</html>