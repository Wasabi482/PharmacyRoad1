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

            $invalid_items = []; // Initialize an array to keep track of invalid items

            foreach ($items as $item) {
                $qty = $item['quantity'];
                $item_name = $item['item_name'];

                $sum_qty = "SELECT sum(item_qty) as total_qty FROM warehouse WHERE item_name= '$item_name'";
                $q = mysqli_query($conn, $sum_qty);
                $row = mysqli_fetch_assoc($q);
                $total_qty = (int) $row['total_qty'];
                $requested_qty = $qty;
                $diff = $total_qty - $requested_qty;

                if ($total_qty < $requested_qty && $diff < 0) {
                    // Add the invalid item to the array
                    $invalid_items[] = ['item_name' => $item_name, 'total_qty' => $total_qty];
                }
            }

            if (!empty($invalid_items)) {
                // There are invalid items, so we'll display the error messages for all of them
                foreach ($invalid_items as $invalid_item) {
                    echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'No more items with the name {$invalid_item['item_name']} found in the warehouse. Max Quantity is only {$invalid_item['total_qty']}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../../pages/user/user_frontend.php';
                    }
                });
                </script>";
                }
            } else {
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
                        foreach ($items as $item) {
                            $item_name = $item['item_name'];
                            $qty_to_deduct = $item['quantity'];

                            // Keep updating the warehouse inventory until the quantity to deduct is zero
                            while ($qty_to_deduct > 0) {
                                // Select the row with the earliest expiry date for the current item that has a quantity greater than zero
                                $select = "SELECT warehouse_code, item_qty FROM warehouse WHERE item_name = '$item_name' AND item_qty > 0 ORDER BY expiry_date ASC LIMIT 1";
                                $result = $conn->query($select);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $current_qty = $row['item_qty'];
                                    $warehouse_code = $row['warehouse_code'];

                                    if ($current_qty <= $qty_to_deduct) {
                                        // If current stock is less than or equal to the quantity to deduct, delete the row
                                        $delete = "DELETE FROM warehouse WHERE warehouse_code = '$warehouse_code'";
                                        $qty_to_deduct -= $current_qty; // Deduct the current stock from the quantity to deduct

                                        // Execute the delete query
                                        if ($conn->query($delete) !== TRUE) {
                                            echo "Error deleting record: " . $conn->error;
                                            break; // Exit the loop if an error occurs
                                        }
                                    } else {
                                        // If current stock is more than the quantity to deduct, update the quantity
                                        $update = "UPDATE warehouse SET item_qty = item_qty - $qty_to_deduct WHERE warehouse_code = '$warehouse_code'";
                                        $qty_to_deduct = 0; // Set quantity to deduct to zero as we've deducted the required quantity 

                                        if ($conn->query($update) !== TRUE) {
                                            echo "Error updating record: " . $conn->error;
                                            break; // Exit the loop if an error occurs
                                        }
                                    }
                                } else {
                                    echo "<script>
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'No more items with the name $item_name found in the warehouse.',
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '../../pages/user/user_frontend.php';
                                    }
                                });
                                </script>";
                                    break; // Exit the loop if no more items are found
                                }
                            }
                        }

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
            }
        } else {
            echo "Missing required fields.";
        }
    }
    ?>

</body>

</html>