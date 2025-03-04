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



    if (isset($_POST['save_changes'])) {
        // Retrieve and sanitize form data
        $code = mysqli_real_escape_string($conn, $_POST['code']);
        $new_unit_price = mysqli_real_escape_string($conn, $_POST['new_unit_price']);
        $new_mark_up = mysqli_real_escape_string($conn, $_POST['new_mark_up']);
        $new_mark_up_dec = $new_mark_up / 100;
        $new_price = ($new_mark_up_dec * $new_unit_price) + $new_unit_price;
        $new_price = number_format($new_price, 2);
        if (fmod($new_price, 1) < 0.50) {
            // Round down
            $new_price = floor($new_price);
        } else {
            // Round up
            $new_price = ceil($new_price);
        }

        // Update the items table with the new values
        $update_query = "UPDATE items SET unit_price = '$new_unit_price', mark_up = '$new_mark_up', price = '$new_price' WHERE code = '$code'";
        $update_result = mysqli_query($conn, $update_query);

        if ($update_result) {
            echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'Success!',
                   text: 'Item updated successfully!',
                   icon: 'success',
                   confirmButtonText: 'Back To Lists'
               }).then(() =>{window.location.href = '../../pages/admin/admin_view_items.php';});
               </script>
           </div>
       </main>";
        } else {
            die('Error: ' . mysqli_error($conn));
        }
    }


    if (isset($_POST['click_edit_item'])) {
        $code = $_POST['code'];


        $data = "SELECT * FROM items WHERE code = '$code'";
        $result = mysqli_query($conn, $data);

        if (!$result) {
            die('Error: ' . mysqli_error($conn));
        }
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $code = $row['code'];
                $price = $row['price'];

                echo "<form method='post' action='../../actions/admin/admin_edit_item.php'>";

                echo "<h5>" . "Item Name: " . $row['item_name'] . "</h5>";
                echo "<h6>" . "<b>Current Unit Price: </b>" . $row['unit_price'] . "</h6>";
                echo "<label for='new_unit_price'>New Unit  Price: </label><br/>
            <input type='number' id='new_unit_price' name='new_unit_price' style='width: 100px; margin-top:1px !important;' required  min='0.01' step='.01'><br />";
                echo "<h6>" . "<b>Current Mark up: </b> " . $row['mark_up'] . "%" . "</h6>";
                echo "<label for='new_unit_price'>New Mark Up: </label><br />
            <input type='number' id='new_mark_up' name='new_mark_up' style='width: 100px;' required min='0.01' step='.01' ><br/>";
                echo "<input type='hidden' name='code' value='$code'><br/>";
                echo "<input type='submit' name='save_changes' class='btn btn-primary' value='Save Changes'><br/>
            <a href='../../pages/admin/admin_view_items.php' class='btn btn-danger mt-2'>Cancel</a>";

                echo "</form";
            }
        } else {
            echo "No data found!";
        }
    }



    ?>

</body>

</html>