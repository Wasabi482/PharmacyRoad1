<?php
include '../../database/config.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    </link>
    <link rel="stylesheet" href="../../css/add_item.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Add New Item - Admin</title>
</head>

<body>
    <?php

    if (isset($_POST['submit'])) {

        $input_name = mysqli_real_escape_string($conn, $_POST['item_name']);
        $unit_price = $_POST['unit_price'];
        $mark_up = $_POST['mark_up'];
        $price = $_POST['selling_price'];

        $unit_type = $_POST['unit_type'];
        $unit_qty = $_POST['unit_qty'];

        $item_name = $input_name . " " . $unit_qty . $unit_type;
        $item_name = strtoupper($item_name);
        $indication = strtoupper($_POST['indication']);
        $type = $_POST['type'];
        $classification = $_POST['class'];
        $vendor_name = $_POST['vendor_name'];

        // echo $item_name;
        // echo $unit_price;
        // echo $mark_up;
        // echo $price;
        // echo $indication;
        // echo $type;
        // echo $classification;
        // echo $vendor_name;


        $select = "SELECT * FROM items WHERE item_name = '$item_name'";

        $result = mysqli_query($conn, $select);

        if (mysqli_num_rows($result) > 0) {
            echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
               <div class='content'>
                   <script>
                   Swal.fire({
                       title: 'Error!',
                       text:  'Item  Already Exists',
                       icon: 'error',
                       confirmButtonText: 'Add New'
                   }).then(() =>{window.location.href = '../../pages/admin/admin_view_items.php';});
                   </script>
               </div>
           </main>
                ";
        } else {

            $insert = "INSERT INTO items(item_name,what_for,unit_price, mark_up, price,type,classification, vendor_name) VALUES('$item_name','$indication','$unit_price','$mark_up','$price','$type','$classification','$vendor_name')";
            $insert_to_items = mysqli_query($conn, $insert);

            if ($insert_to_items) {
                $token = explode(" ", $item_name);
                $insert_tokens = [];
                $inserted_tokens = [];
                $total = count($token);
                foreach ($token as $t) {
                    $select = "SELECT * FROM training_items WHERE words = '$t'";
                    $result = mysqli_query($conn, $select);
                    if (mysqli_num_rows($result) == 0) {
                        $insert_tokens[] = $t;
                    } else {
                        $inserted_tokens[] = $t;
                    }
                }

                if ($total > 0 && count($insert_tokens) > 0 && count($insert_tokens) < $total) {
                    foreach ($insert_tokens as $insert_token) {
                        $insert_query = "INSERT INTO training_items (words) VALUES ('$insert_token')";
                        if (mysqli_query($conn, $insert_query)) {
                            echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
                                    <div class='content'>
                                        <script>
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Added New Item and added some training words',
                                            icon: 'success',
                                            confirmButtonText: 'Back To Lists'
                                        }).then(() =>{window.location.href = '../../pages/admin/admin_view_items.php';});
                                        </script>
                                    </div>
                                </main>";
                        }
                    }
                } elseif ($total == count($insert_tokens)) {
                    foreach ($insert_tokens as $insert_token) {
                        $insert_query = "INSERT INTO training_items (words) VALUES ('$insert_token')";
                        if (mysqli_query($conn, $insert_query)) {
                            echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
                                    <div class='content'>
                                        <script>
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Added New Item and added all new training words',
                                            icon: 'success',
                                            confirmButtonText: 'Back To Lists'
                                        }).then(() =>{window.location.href = '../../pages/admin/admin_view_items.php';});
                                        </script>
                                    </div>
                                </main>";
                        }
                    }
                } else {
                    echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
                                    <div class='content'>
                                        <script>
                                        Swal.fire({
                                            title: 'Success!',
                                            text: 'Added New Item. Training already done on previous items',
                                            icon: 'success',
                                            confirmButtonText: 'Back To Lists'
                                        }).then(() =>{window.location.href = '../../pages/admin/admin_view_items.php';});
                                        </script>
                                    </div>
                                </main>";
                }
            } else {
                echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
                                    <div class='content'>
                                        <script>
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'error on insertion',
                                            icon: 'error',
                                            confirmButtonText: 'Back To Lists'
                                        }).then(() =>{window.location.href = '../../pages/admin/admin_view_items.php';});
                                        </script>
                                    </div>
                                </main>";
            }
        }
    } elseif (isset($_POST['submit_others'])) {
        $input_name = mysqli_real_escape_string($conn, $_POST['item_name']);
        $unit_price = $_POST['unit_price'];
        $mark_up = $_POST['mark_up'];
        $price = $_POST['selling_price'];

        $unit_type = $_POST['unit_type'];
        $unit_qty = $_POST['unit_qty'];

        $item_name = $input_name . " " . $unit_qty . $unit_type;
        $item_name = strtoupper($item_name);
        $type = $_POST['type'];
        $classification = $_POST['class'];
        $vendor_name = $_POST['vendor_name'];

        // echo $item_name;
        // echo $unit_price;
        // echo $mark_up;
        // echo $price;
        // echo $type;
        // echo $classification;
        // echo $vendor_name;


        $select = "SELECT * FROM items WHERE item_name = '$item_name'";

        $result = mysqli_query($conn, $select);

        if (mysqli_num_rows($result) > 0) {
            echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
               <div class='content'>
                   <script>
                   Swal.fire({
                       title: 'Error!',
                       text:  'Item  Already Exists',
                       icon: 'error',
                       confirmButtonText: 'Add New'
                   }).then(() =>{window.location.href = '../../pages/admin/admin_view_items.php';});
                   </script>
               </div>
           </main>
                ";
        } else {

            $insert = "INSERT INTO items(item_name,unit_price, mark_up, price,type,classification, vendor_name) VALUES('$item_name','$unit_price','$mark_up','$price','$type','$classification','$vendor_name')";
            $insert_to_items = mysqli_query($conn, $insert);
            echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
               <div class='content'>
                   <script>
                   Swal.fire({
                       title: 'Success!',
                       text:  'New item added',
                       icon: 'success',
                       confirmButtonText: 'Add New'
                   }).then(() =>{window.location.href = '../../pages/admin/admin_view_items.php';});
                   </script>
               </div>
           </main>
                ";
        }
    }

    ?>

</body>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>