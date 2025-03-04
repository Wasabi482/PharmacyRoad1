<?php
include '../../database/config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
    <link rel="stylesheet" href="../../css/add_order.css">
    <title>Add Order</title>
    <link rel="icon" href="../../img/icon copy.png" type="image/x-icon" />

</head>

<body>
    <?php
    if (!isset($_SESSION['items'])) {
        $_SESSION['items'] = [];
    }
    if (isset($_POST['add_item'])) {
        if (isset($_POST['item_name']) && isset($_POST['item_qty'])) {
            $item_name = $_POST['item_name'];
            $receive_qty = $_POST['item_qty'];
            $priceQuery = mysqli_query($conn, "SELECT unit_price FROM items WHERE item_name = '$item_name'");
            $priceResult = mysqli_fetch_assoc($priceQuery);
            $price = $priceResult['unit_price'];
            $subtotal = $price * $receive_qty;
            $expiry_date = $_POST['expiry_date'];
            $date_received = $_POST['date_received_check'];
            $batch_no = $_POST['batch_no'];
            // echo $receipt_no;
            // echo $vendor;
            // echo $date_received;
            // echo $item_name;
            // echo $receive_qty;
            // echo $price;
            // echo $subtotal;
            // echo $expiry_date;



            $expiry_date_obj = new DateTime($expiry_date);
            $date_received_obj = new DateTime($date_received);
            $interval = $date_received_obj->diff($expiry_date_obj);
            if ($interval->y < 1) {
                // Send an error message using SweetAlert2
                echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'The expiry date must be at least 1 year from the date received.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                }).then(() => {
                    window.location.href = '../../pages/user/user_rdu_received_item.php';
                });
                </script>";
                exit; // Stop further execution if the condition is met
            }
            $item_data = [
                'item_name' => $item_name,
                'price' => $price,
                'quantity' => $receive_qty,
                'subtotal' => $subtotal,
                'expiry_date' => $expiry_date,
                'batch_no' => $batch_no,
            ];
            $found = false;
            foreach ($_SESSION['items'] as $key => $prodSessionItem) {
                if ($prodSessionItem['item_name'] == $item_name && $prodSessionItem['expiry_date'] == $expiry_date) {
                    $found = true;
                    $new_qty = $prodSessionItem['quantity'] + $receive_qty;
                    $new_subtotal = $prodSessionItem['subtotal'] + $subtotal;

                    $_SESSION['items'][$key] = [
                        'item_name' => $item_name,
                        'price' => $price,
                        'quantity' => $new_qty,
                        'subtotal' => $new_subtotal,
                        'expiry_date' => $expiry_date,
                        'batch_no' => $batch_no,
                    ];
                    break; // Item updated, no need to continue the loop
                }
            }

            if (!$found) {
                // Item not found in the session, add as new
                $_SESSION['items'][] = $item_data;
            }
            echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'Item Added',
                   text: 'Item Added" . " " . $item_name;
            echo "',
                   icon: 'success',
                   confirmButtonText: 'Ok'
               }).then(() =>{window.location.href = '../../pages/user/user_rdu_received_item.php';});
               </script>
           </div>
       </main>";
        }
    }

    ?>