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
    <title>Checkout</title>
    <link rel="icon" href="../../img/icon copy.png" type="image/x-icon" />

</head>

<body>
    <div class='container' id='container2'>
        <div class="card-header">
            <center>
                <h1><b>Road 1 Pharmacy</b></h1>
                <h3>Your One Stop Pharmacy</h3>
                <h3>By:</h3>
                <h4>Address</h4>
                <h4>Sjdm Bulacan</h4>
                <h2>📝 Order Summary</h2>
            </center>
        </div>
        <div class='row'>
            <div class='col-md-12'>
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
                                                                <th scope='col'>Price</th>
                                                                <th scope='col'>Quantity</th>
                                                                <th scope='col'>Subtotal</th>
                                                                <th scope='col'>Type</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $gen = 0; // Initialize the "gen" variable to 0

                                                            if (!empty($_POST['items'])) {
                                                                foreach ($_POST['items'] as $item) {
                                                                    echo "<tr>";
                                                                    echo "<td scope='row'> " . htmlspecialchars($item['id']) . "</td>";
                                                                    echo "<td scope='row'> " . htmlspecialchars($item['item_name']) . "</td>";
                                                                    echo "<td scope='row'>" . htmlspecialchars($item['price']) . "</td>";
                                                                    echo "<td scope='row'>" . htmlspecialchars($item['quantity']) . "</td>";
                                                                    echo "<td scope='row'>" . htmlspecialchars($item['subtotal']) . "</td>";
                                                                    echo "<td scope='row'>" . htmlspecialchars($item['type']) . "</td>";
                                                                    echo "</tr>";

                                                                    if ($item['type'] === 'Generic') {
                                                                        $gen += $item['subtotal']; // Add the subtotal to the "gen" variable
                                                                    }
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
                            <div class="text-end">
                                <?php echo "<h4>" . htmlspecialchars($total) . "</h4>"; ?>
                                <div class="form-group">
                                    <form action="process_checkout.php" method="post">
                                        <label for="mode_of_payment">Payment Mode</label>
                                        <select class="form-control" id="mode_of_payment" name="mode_of_payment" style='width:20%;'>
                                            <option value="Cash">Cash</option>
                                            <option value="Gcash">Gcash</option>
                                        </select>
                                        <label for="amountTendered">Amount Tendered:</label>
                                        <input required min="<?= $total ?>" type="number" name="tender_amount" class="form-control" id="amountTendered" placeholder="Enter amount tendered" style='width:20%;'>
                                </div>
                                <h4 id="change">Change: </h4>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <center>
                <h2>Thank You</h2>
            </center>
        </div>
    </div>
    <center class="mt-2">
        <?php
        $curr_user = $_SESSION['username'];

        ?>
        <!-- Pass the items and total as hidden fields -->
        <input type="hidden" name="items" value='<?php echo json_encode($_POST['items']); ?>'>
        <input type="hidden" name="total" value='<?php echo htmlspecialchars($total); ?>'>
        <input type="hidden" name="gen" value='<?php echo $gen ?>'>
        <input type="hidden" name="curr_user" value='<?php echo $curr_user ?>'>
        <button type="submit" class="btn btn-primary mb-0">Proceed</button>
        </form>
        <a href="../../pages/user/user_frontend.php" class="btn btn-danger">Cancel</a>
    </center>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        var proceedButton = $('button[type="submit"]');

        $('#amountTendered').on('input', function() {
            var total = <?php echo json_encode($total); ?>;
            var amountTendered = $(this).val();
            var change = amountTendered - total;
            $('#change').text('Change: ' + (change >= 0 ? change.toFixed(2) : 'Insufficient Amount'));

            // Disable or enable the proceed button based on the amount tendered
            if (change < 0) {
                proceedButton.prop('disabled', true);
            } else {
                proceedButton.prop('disabled', false);
            }
        });

        // Initialize the state of the button based on the initial value of the input
        $('#amountTendered').trigger('input');
    });
</script>

</html>