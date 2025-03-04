<?php
include '../../database/config.php';
include '../../actions/session_check.php'; ?>

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
    <title>Adjust Item</title>
</head>

<body>
    <?php

    if (isset($_POST['submit_zero'])) {

        $item_name = $_POST['item_name'];
        $qty = $_POST['qty'];
        $expiry_date = $_POST['expiry_date'];
        $batch_no = $_POST['batch_no'];
        $date_received = $_POST['date_received'];
        $time = $_POST['time'];
        $vendor = $_POST['vendor_name'];
        $reason = $_POST['reason'] . " " . $date_received . "-" . $time;

        $insertQuery = "INSERT INTO warehouse (item_name, item_qty, expiry_date,batch_no, vendor_name) VALUES 
            ('$item_name', '$qty', '$expiry_date','$batch_no', '$vendor')";
        $insertResult = mysqli_query($conn, $insertQuery);
        if ($insertResult) {
            $insertQuery2 = "INSERT INTO deliver_received (receipt_trans_number, vendor_name, date_received, received_by) VALUES 
                ('$reason',  '$vendor', '$date_received','Admin')";
            $insertResult2 = mysqli_query($conn, $insertQuery2);
            if ($insertQuery2) {
                $last_id = $conn->insert_id;
                $insertQuery4 = "INSERT INTO delivered_items (last_id,receipt_trans_number, item_name,  item_qty, expiry_date, batch_no) VALUES (
                            '$last_id','$reason', '$item_name',  '$qty', '$expiry_date','$batch_no')";
                $insertResult4 = mysqli_query($conn, $insertQuery4);
                if ($insertResult4) {
                    echo "<script>
                            Swal.fire({
                                title: 'Success!',
                                text: 'A new row has been inserted in Warehouse due to NO COMMON Expiry date and Vendor Name.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '../../pages/admin/admin_view_warehouse.php';
                                }
                            });
                        </script>";
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['submit_add'])) {
        $item_name = $_POST['item_name'];
        $qty = $_POST['qty'];
        $expiry_date = $_POST['expiry_date'];
        $date_received = $_POST['date_received'];
        $batch_no = $_POST['batch_no'];
        $time = $_POST['time'];
        $vendor = $_POST['vendor_name'];
        $reason = $_POST['reason'] . " " . $date_received . "-" . $time;


        $checkQuery = "SELECT * FROM warehouse WHERE item_name='$item_name' AND expiry_date='$expiry_date' AND vendor_name='$vendor'";
        $checkResult = mysqli_query($conn, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'An item in the warehouse has the same Item name. Expiry date and Vendor Name. Find it and use Adjust instead',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../../pages/admin/admin_view_warehouse.php';
                        }
                    });
                </script>";
        } else {
            $insertQuery = "INSERT INTO warehouse (item_name, item_qty, expiry_date,batch_no, vendor_name) VALUES
    ('$item_name', '$qty', '$expiry_date','$batch_no', '$vendor')";
            $insertResult = mysqli_query($conn, $insertQuery);
            if ($insertResult) {
                $insertQuery2 = "INSERT INTO deliver_received (receipt_trans_number, vendor_name, date_received, received_by) VALUES
    ('$reason', '$vendor', '$date_received','Admin')";
                $insertResult2 = mysqli_query($conn, $insertQuery2);
                if ($insertQuery2) {
                    $last_id = $conn->insert_id;
                    $insertQuery4 = "INSERT INTO delivered_items (last_id,receipt_trans_number, item_name, item_qty, expiry_date,batch_no) VALUES (
    '$last_id','$reason', '$item_name', '$qty', '$expiry_date','$batch_no')";
                    $insertResult4 = mysqli_query($conn, $insertQuery4);
                    if ($insertResult4) {
                        echo "<script>
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'A new item has been adjusted in Warehouse due to NO COMMON Expiry date and Vendor Name.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '../../pages/admin/admin_view_warehouse.php';
                                    }
                                });
                            </script>";
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    } elseif (isset($_POST['submit_edit'])) {
        $curr_qty = $_POST['curr_qty'];
        $process = $_POST['process'];
        $qty = $_POST['qty'];
        $id = $_POST['id'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $expiry_date = $_POST['expiry_date'];
        $item_name = $_POST['item_name'];

        if ($process === 'Add') {
            $new_qty = $curr_qty + $qty;
            $reason = "Added to warehouse" . " " . $date . "-" . $time;
            $updateQuery = "UPDATE warehouse SET item_qty = '$new_qty' WHERE warehouse_code = '$id'";
            $updateResult = mysqli_query($conn, $updateQuery);
            if ($updateResult) {
                $insertQuery = "INSERT INTO deliver_received (receipt_trans_number, date_received, received_by) VALUES ('$reason',  '$date','Admin')";
                $insertResult = mysqli_query($conn, $insertQuery);
                if ($insertResult) {
                    $last_id = $conn->insert_id;
                    $insertQuery2 = "INSERT INTO delivered_items (last_id,receipt_trans_number, item_name,  item_qty, expiry_date) VALUES (
                                '$last_id','$reason', '$item_name',  '$qty', '$expiry_date')";
                    $insertResult2 = mysqli_query($conn, $insertQuery2);
                    if ($insertResult2) {
                        echo "<script>
                            Swal.fire({
                                title: 'Success!',
                                text: 'Item quantity has been adjusted successfully in the warehouse database.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '../../pages/admin/admin_view_warehouse.php';
                                }
                            });
                        </script>";
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            $new_qty = $curr_qty - $qty;
            $reason = "Subtract from warehouse" . " " . $date . "-" . $time;
            if ($new_qty > 0) {
                $updateQuery = "UPDATE warehouse SET item_qty = '$new_qty' WHERE warehouse_code = '$id'";
                $updateResult = mysqli_query($conn, $updateQuery);
                if ($updateResult) {
                    $insertQuery = "INSERT INTO deliver_received (receipt_trans_number, date_received, received_by) VALUES ('$reason',  '$date','Admin')";
                    $insertResult = mysqli_query($conn, $insertQuery);
                    if ($insertResult) {
                        $last_id = $conn->insert_id;
                        $insertQuery2 = "INSERT INTO delivered_items (last_id,receipt_trans_number, item_name,  item_qty, expiry_date) VALUES (
                                '$last_id','$reason', '$item_name',  '$qty', '$expiry_date')";
                        $insertResult2 = mysqli_query($conn, $insertQuery2);
                        if ($insertResult2) {
                            echo "<script>
                            Swal.fire({
                                title: 'Success!',
                                text: 'Item quantity has diminished successfully in the warehouse database.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '../../pages/admin/admin_view_warehouse.php';
                                }
                            });
                        </script>";
                        }
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                $reason = "Subtract from warehouse" . " " . $date . "-" . $time;
                $deleteQuery = "DELETE from warehouse WHERE warehouse_code = '$id'";
                $deleteResult = mysqli_query($conn, $deleteQuery);
                if ($deleteResult) {
                    $insertQuery = "INSERT INTO deliver_received (receipt_trans_number, date_received, received_by) VALUES ('$reason',  '$date','Admin')";
                    $insertResult = mysqli_query($conn, $insertQuery);
                    if ($insertResult) {
                        $last_id = $conn->insert_id;
                        $insertQuery2 = "INSERT INTO delivered_items (last_id,receipt_trans_number, item_name,  item_qty, expiry_date) VALUES (
                                '$last_id','$reason', '$item_name',  '$qty', '$expiry_date')";
                        $insertResult2 = mysqli_query($conn, $insertQuery2);
                        if ($insertResult2) {
                            echo "<script>
                                    Swal.fire({
                                        title: 'Success!',
                                        text: 'Item has been deleted successfully in the warehouse database.',
                                        icon: 'warning',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = '../../pages/admin/admin_view_warehouse.php';
                                        }
                                    });
                                </script>";
                        }
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                }
            }
        }
    }
    ?>