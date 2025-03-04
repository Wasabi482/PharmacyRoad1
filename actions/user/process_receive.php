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
    <title>Receiving</title>
    <link rel="icon" href="../../img/icon copy.png" type="image/x-icon" />

</head>

<body>


    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['items']) && isset($_POST['total'])) {
        $items = json_decode($_POST['items'], true);
        $total = $_POST['total'];
        $receipt_trans_number = $_POST['receipt_trans_number'];
        $date_received = $_POST['date_received'];
        $vendor_name = $_POST['vendor_name'];
        $curr_user = $_POST['curr_user'];
        foreach ($items as  $item) {
            $item_name = $item['item_name'];
            $unit_price = $item['price'];
            $qty = $item['quantity'];
            $expiry_date = $item['expiry_date'];
            $batch_no = $item['batch_no'];
            $subtotal  = $unit_price * $qty;
        }

        $checkQuery = "SELECT * FROM warehouse WHERE item_name='$item_name' AND expiry_date='$expiry_date' AND vendor_name='$vendor_name'";
        $checkResult = mysqli_query($conn, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            // If they exist, update item_qty
            foreach ($items as  $item) {
                $item_name = $item['item_name'];
                $unit_price = $item['price'];
                $qty = $item['quantity'];
                $expiry_date = $item['expiry_date'];
                $subtotal  = $unit_price * $qty;
                $updateQuery = "UPDATE warehouse SET item_qty=item_qty+'$qty' WHERE expiry_date='$expiry_date' AND vendor_name='$vendor_name'";
                $updateResult = mysqli_query($conn, $updateQuery);
            }

            if ($updateResult) {
                $date_received;
                $insertQuery1 = "INSERT INTO deliver_received (receipt_trans_number, vendor_name, date_received, total, received_by) VALUES 
                ('$receipt_trans_number',  '$vendor_name', '$date_received','$total','$curr_user')";
                $insertResult1 = mysqli_query($conn, $insertQuery1);
                if ($insertResult1) {
                    foreach ($items as $item) {
                        $item_name = $item['item_name'];
                        $unit_price = $item['price'];
                        $qty = $item['quantity'];
                        $expiry_date = $item['expiry_date'];
                        $subtotal  = $unit_price * $qty;
                        $last_id = $conn->insert_id;
                        $insertQuery3 = "INSERT INTO delivered_items (last_id,receipt_trans_number, item_name, unit_price, item_qty, expiry_date,batch_no, subtotal) VALUES (
                            '$last_id','$receipt_trans_number', '$item_name', '$unit_price', '$qty', '$expiry_date','$batch_no' '$subtotal')";
                        $insertResult3 = mysqli_query($conn, $insertQuery3);
                        if ($insertResult3) {
                            unset($_SESSION['items']);
                            echo "<script>
                            Swal.fire({
                                title: 'Success!',
                                text: 'Item quantity has been updated successfully in the warehouse database.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '../../pages/user/user_rdu_received_item.php';
                                }
                            });
                        </script>";
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            foreach ($items as $item) {
                $item_name = $item['item_name'];
                $unit_price = $item['price'];
                $qty = $item['quantity'];
                $expiry_date = $item['expiry_date'];
                $subtotal  = $unit_price * $qty;
                $insertQuery = "INSERT INTO warehouse (item_name, item_qty, expiry_date,batch_no, vendor_name) VALUES 
            ('$item_name', '$qty', '$expiry_date','$batch_no', '$vendor_name')";
                $insertResult = mysqli_query($conn, $insertQuery);
            }
            if ($insertQuery) {
                $date_received;
                $insertQuery2 = "INSERT INTO deliver_received (receipt_trans_number, vendor_name, date_received, total, received_by) VALUES 
                ('$receipt_trans_number',  '$vendor_name', '$date_received','$total','$curr_user')";
                $insertResult2 = mysqli_query($conn, $insertQuery2);
                if ($insertResult2) {
                    foreach ($items as $item) {
                        $item_name = $item['item_name'];
                        $unit_price = $item['price'];
                        $qty = $item['quantity'];
                        $expiry_date = $item['expiry_date'];
                        $subtotal  = $unit_price * $qty;
                        $last_id = $conn->insert_id;
                        $insertQuery4 = "INSERT INTO delivered_items (last_id,receipt_trans_number, item_name, unit_price, item_qty, expiry_date,batch_no, subtotal) VALUES (
                            '$last_id','$receipt_trans_number', '$item_name', '$unit_price', '$qty', '$expiry_date','$batch_no', '$subtotal')";
                        $insertResult4 = mysqli_query($conn, $insertQuery4);
                        if ($insertResult4) {
                            unset($_SESSION['items']);
                            echo "<script>
                            Swal.fire({
                                title: 'Success!',
                                text: 'A new row has been inserted in Warehouse due to NO COMMON Expiry date and Vendor Name.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '../../pages/user/user_rdu_received_item.php';
                                }
                            });
                        </script>";
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
    ?>