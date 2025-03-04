<?php
include '../../database/config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the 'items' and 'total' are set in the POST request
    if (isset($_POST['items']) && isset($_POST['total']) && isset($_POST['user'])) {
        // Retrieve the total
        $total = $_POST['total'];
        $user = $_POST['user'];
        $receipt_trans_number = $_POST['receipt_trans_number'];
        $vendor_name = $_POST['vendor_name'];
        $date_received = $_POST['date_received'];
        // Process each item
        foreach ($_POST['items'] as $item) {
        }
    }
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
    <link rel="stylesheet" href="../../css/checkout.css">
    <title>Receive Items</title>
    <link rel="icon" href="../../img/icon copy.png" type="image/x-icon" />

</head>

<body>
    <div class='container' id='container2'>
        <div class="card-header">
            <center>
                <h2>📝 Receive Summary</h2>
            </center>
        </div>
        <div class='row'>
            <div class='col-md-12'>
                <h2><?php echo "Receipt No.: " . $receipt_trans_number; ?></h2><br />
                <h3><?php echo "Vendor: " . $vendor_name; ?></h3><br />
                <h3><?php echo "Date: " . $date_received; ?></h3><br />
                <div class='card' style='width: 69.rem;'>
                    <div class='card-body'>
                        <section class="intro">
                            <div class="gradient-custom-1 h-100">
                                <div class="mask d-flex align-items-center h-100">
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <div class="col-12">

                                                <div class="table-responsive bg-white">
                                                    <table class="table mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th scope='col'>No</th>
                                                                <th scope='col'>Item Name</th>
                                                                <th scope='col'>Unit Price</th>
                                                                <th scope='col'>Quantity</th>
                                                                <th scope='col'>Subtotal</th>
                                                                <th scope='col'>Expiry Date</th>
                                                                <th scope='col'>Batch No</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            if (!empty($_POST['items'])) {
                                                                foreach ($_POST['items'] as $item) {
                                                                    echo "<tr>";
                                                                    echo "<td scope='row'> " . htmlspecialchars($item['id']) . "</td>";
                                                                    echo "<td scope='row'> " . htmlspecialchars($item['item_name']) . "</td>";
                                                                    echo "<td scope='row'>" . htmlspecialchars($item['price']) . "</td>";
                                                                    echo "<td scope='row'>" . htmlspecialchars($item['quantity']) . "</td>";
                                                                    echo "<td scope='row'>" . htmlspecialchars($item['subtotal']) . "</td>";
                                                                    echo "<td scope='row'>" . htmlspecialchars($item['expiry_date']) . "</td>";
                                                                    echo "<td scope='row'>" . htmlspecialchars($item['batch_no']) . "</td>";
                                                                    echo "</tr>";
                                                                }
                                                            } else {
                                                                echo "<p>No items in the order.</p>";
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo "<h4>" . htmlspecialchars($total) . "</h4>"; ?>
                    </div>
                </div>
                </section>
            </div>
        </div>
    </div>
    </div>
    </div>
    <center class="mt-2">
        <form action="process_receive.php" method="post">
            <?php
            $curr_user = $_SESSION['username'];
            ?>
            <!-- Pass the items and total as hidden fields -->
            <input type="hidden" name="items" value='<?php echo json_encode($_POST['items']); ?>'>
            <input type="hidden" name="total" value='<?php echo htmlspecialchars($total); ?>'>
            <input type="hidden" name="curr_user" value='<?php echo $curr_user ?>'>
            <input type="hidden" name="receipt_trans_number" value='<?php echo $receipt_trans_number ?>'>
            <input type="hidden" name="vendor_name" value='<?php echo $vendor_name ?>'>
            <input type="hidden" name="date_received" value='<?php echo $date_received ?>'>
            <button type="submit" class="btn btn-primary mb-2">Proceed</button>
        </form>
        <a href="../../pages/user/user_rdu_received_item.php" class="btn btn-danger">Cancel</a>
    </center>
</body>