<?php
include '../../database/config.php';
include '../../actions/session_check.php';
include '../../actions/user_midware.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Push Order Form</title>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    </link>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.my-select').select2();
        });
    </script>
</head>

<body>
    <?php
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Check if all fields are filled out
        if (!empty($_POST['item_name']) && !empty($_POST['reason']) && !empty($_POST['qty'])) {
            // Prepare an insert statement
            $sql = "INSERT INTO push_orders (item_name, reason, qty,date_reported,status) VALUES (?, ?, ?, ?, ?)";

            if ($stmt = $conn->prepare($sql)) {
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ssiss", $item_name, $reason, $qty, $today, $status);

                // Set parameters and execute
                $item_name = $_POST['item_name'];
                $reason = $_POST['reason'];
                $qty = $_POST['qty'];
                $today =  date("Y-m-d");

                $status = $_POST['status'];

                if ($stmt->execute()) {
                    // Use JavaScript for redirection after the alert
                    echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
                   <div class='content'>
                       <script>
                       Swal.fire({
                           title: 'Success!',
                           text: 'Order Pushed to Admin',
                           icon: 'success',
                           confirmButtonText: 'Ok'
                       }).then(() =>{window.location.href = 'user_rdu_send.php';});
                       </script>
                   </div>
               </main>";
                    exit();
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "');</script>";
                }

                // Close statement
                $stmt->close();
            } else {
                echo "<script>alert('Error: " . $conn->error . "');</script>";
            }
        } else {
            // Send error message
            echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
               <div class='content'>
                   <script>
                   Swal.fire({
                       title: 'Insufficient Data!',
                       text: 'Please fill all necessary info',
                       icon: 'warning',
                       confirmButtonText: 'Ok'
                   }).then(() =>{window.location.href = 'user_rdu_send.php';});
                   </script>
               </div>
           </main>";
        }
    }
    ?>
    <div class="form-wrapper">
        <form action="user_rdu_push.php" method="post"> <!-- Make sure this action points to the correct PHP file -->
            <b>Push Order</b> <br>
            <label for="item_name">Item Name</label><br>
            <select name="item_name" id="item_name" class='my-select'>
                <!-- PHP code to populate the item_name options -->
                <?php
                $data = "SELECT * FROM items ORDER BY item_name ASC";
                $result = mysqli_query($conn, $data);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $item_name = $row['item_name'];


                        $item_name_new = $item_name;

                        echo "<option value='$item_name_new'>$item_name_new</option>";
                    }
                } else {
                    echo "<option value=''>No data found!</option>";
                }
                ?>
            </select><br>
            <label for="reason">Reason</label><br>
            <select name="reason" id="reason" class="my-select">
                <option value="Low_on_Stock">Low on Stock</option>
                <option value="customer_request">Customer Request</option>
            </select><br>
            <label for="qty">Quantity</label><br>
            <input type="number" name="qty" id="qty" required><br>
            <input type="hidden" name="status" value="unread">
            <button type="submit" class="btn btn-primary mt-2">Submit</button>
        </form>
    </div>




    <!-- Include jQuery -->

</body>

</html>