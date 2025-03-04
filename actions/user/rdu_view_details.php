<?php
include '../../database/config.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    </link>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Document</title>
</head>

<body>

    <?php
    if (isset($_POST['click_view_details'])) {
        $id = $_POST['id'];
        $total = $_POST['total'];
        $date_received = $_POST['date_received'];

        $trans_items = "SELECT * FROM delivered_items WHERE receipt_trans_number = '$id'";
        $result = mysqli_query($conn, $trans_items);
        if (!$result) {
            die('Error: ' . mysqli_error($conn));
        }
        if (mysqli_num_rows($result) > 0) {
            echo "
                    <section class='intro'>
                        <div class='gradient-custom-2 h-100'>
                            <div class='mask d-flex align-items-center h-100'>
                                <div class='container'>
                                    <div class='row justify-content-center'>
                                        <div class='col-12'>
                                            <div class='table-responsive'>
                                            <h2>Transaction #: " . $id . "</h2> <br>
                                            <h3>Date: " . $date_received . "</h3> <br>
                                                <table class='table table-dark table-bordered mb-0'>
                                                    <thead>
                                                        <tr>
                                                            <th scope='col'>Item Name</th>
                                                            <th scope='col'>Unit Price</th>
                                                            <th scope='col'>QTY</th>
                                                            <th scope='col'>Expiry date</th>
                                                            <th scope='col'>Batch/Lot #</th>
                                                            <th scope='col'>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                    ";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "    
                                                        <tr>
                                                            <td scope='col' > " . $row['item_name'] . "</td>
                                                            <td scope='col' > " . $row['unit_price'] . "</td>
                                                            <td scope='col' > " . $row['item_qty'] . "</td>
                                                            <td scope='col' > " . $row['expiry_date'] . "</td>
                                                            <td scope='col' > " . $row['batch_no'] . "</td>
                                                            <td scope='col' > " . $row['unit_price'] * $row['item_qty'] . "</td>

                                                        </tr>";
            }
            echo "
                                                    </tbody>
                                                </table>
                                                <h2>Total: " . $total . "</h2><br/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>";
        } else {
            echo " No Results";
        }
    }


    ?>