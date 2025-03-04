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


    if (isset($_POST['add_order'])) {
        if (isset($_POST['item_name']) && isset($_POST['item_qty'])) {
            $item_name = $_POST['item_name'];
            $requested_qty = $_POST['item_qty'];
            $priceQuery = mysqli_query($conn, "SELECT * FROM items WHERE item_name = '$item_name'");
            $priceResult = mysqli_fetch_assoc($priceQuery);
            $type = $priceResult['type'];
            $price = $priceResult['price'];
            $subtotal = $price * $requested_qty;


            $checkItem = mysqli_query($conn, "SELECT * FROM warehouse WHERE item_name = '$item_name'");
            if ($checkItem) {
                if (mysqli_num_rows($checkItem) > 0) {
                    $sum_qty = "SELECT sum(item_qty) as total_qty FROM warehouse WHERE item_name= '$item_name'";
                    $q = mysqli_query($conn, $sum_qty);
                    $row = mysqli_fetch_assoc($q);

                    $total_qty = (int) $row['total_qty'];
                    $requested_qty = (int) $requested_qty;
                    $diff = $total_qty - $requested_qty;


                    if ($total_qty >= $requested_qty && $diff >= 0) {
                        $item_data = [
                            'item_name' => $item_name,
                            'price' => $price,
                            'quantity' => $requested_qty,
                            'type' => $type,
                            'subtotal' => $subtotal,
                        ];
                        $found = false;
                        foreach ($_SESSION['items'] as $key => $prodSessionItem) {
                            if ($prodSessionItem['item_name'] == $item_name) {
                                $found = true;
                                $new_qty = $prodSessionItem['quantity'] + $requested_qty;
                                $new_subtotal = $prodSessionItem['subtotal'] + $subtotal;

                                $_SESSION['items'][$key] = [
                                    'item_name' => $item_name,
                                    'price' => $price,
                                    'quantity' => $new_qty,
                                    'subtotal' => $new_subtotal,
                                    'type' => $type
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
            text: 'Item Added $item_name',
            icon: 'success',
            confirmButtonText: 'Ok'
        }).then(() => {window.location.href = '../../pages/user/user_frontend.php';});
        </script>
    </div>
</main>";
                    } else {
                        echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'Insufficient Stocks!',
                   text: 'The Total Stock qty is only " . $row['total_qty']; //$row['total_qty'];
                        echo "',
                   icon: 'warning',
                   confirmButtonText: 'Ok'
               }).then(() =>{window.location.href = '../../pages/user/user_frontend.php';});
               </script>
           </div>
       </main>";
                        // echo $sum_qty;
                        // echo $requested_qty;
                        // echo $sum_qty - $requested_qty;
                    }
                } else {
                    echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'No item Found',
                   text: 'No Stocks Available";
                    echo "',
                   icon: 'warning',
                   confirmButtonText: 'Ok'
               }).then(() =>{window.location.href = '../../pages/user/user_frontend.php';});
               </script>
           </div>
       </main>";
                }
            } else {
                echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'No item Found',
                   text: 'No Stocks Available";
                echo "',
                   icon: 'warning',
                   confirmButtonText: 'Ok'
               }).then(() =>{window.location.href = '../../pages/user/user_frontend.php';});
               </script>
           </div>
       </main>";
            }
        } else {
            // Handle the case where 'item_name' or 'item_qty' is not set
            echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
                <div class='content'>
                    <script>
                    Swal.fire({
                        title: 'Error',
                        text: 'Item name or quantity is missing.',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    }).then(() => {window.location.href = '../../pages/user/user_frontend.php';});
                    </script>
                </div>
            </main>";
        }
    }


    if (isset($_POST['quantity_in_dec'])) {
        $qty_id = $_POST['id'];
        $qty = $_POST['quantity'];
    }
    ?>
</body>

</html>