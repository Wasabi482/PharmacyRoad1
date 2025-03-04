<?php
include '../../database/config.php';
session_start();
include '../../actions/user_midware.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../../css/add_order.css">
    <?php include 'user_ham.php';
    if (isset($_SESSION['username']) && isset($_SESSION['name'])) {
        $username = $_SESSION['username'];
        $name = $_SESSION['name'];
        echo "<p id ='user'> " . htmlspecialchars($name) . "</p>";
    } else {
        echo "No session found. Please log in.";
    }
    ?>
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0"><img src="../../img/IMG_5789__1_-removebg-preview.png" class="logo-image-navbar h1" alt="logo">Add Transaction
                </h2>
            </div>
            <div class="card-body">
                <form action="../../actions/user/add_order.php" method="post">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="">Item Name</label>
                            <br />
                            <select name="item_name" class="form-select my-select" id="item_name" style="height:30px !important">
                                <option value="">-- Select Item --</option>
                                <?php
                                $data = "SELECT * FROM items";
                                $result = mysqli_query($conn, $data);
                                $json_array = array();
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $item_name = $row['item_name'];
                                        echo "<option value='$item_name'>$item_name</option>";
                                    }
                                } else {
                                    echo "<option value=''>No data found!</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="">Price</label>
                            <br />
                            <b><span id="unit_price_display" style="height:30px !important">0</span></b>
                        </div>
                        <!-- <div class="col-md-2 mb-3">
                            <label for="">Current Qty</label>
                            <br/>
                            <b><span id="current_qty" style="height:30px !important">0</span></b>
                        </div> -->
                        <div class="col-md-2 mb-3">
                            <label for="">Item Quantity</label>
                            <input type="number" name="item_qty" required class="form-control" style="height:30px !important" placeholder="Enter Quantity" min=1>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="">Subtotal</label>
                            <br />
                            <b><span id="subtotal_display" style="height:30px !important">0</span></b>
                        </div>
                        <div class="col-md-3 mb-3 text-end">
                            <br />
                            <button type="submit" name="add_order" class="btn btn-primary">➕Add Order</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h4 class="mb-0"><img src='../../img/IMG_5789__1_-removebg-preview.png' class='logo-image-navbar h1' alt='logo'>Pending Transaction</h4>
            </div>
            <div class="card-body">
                <?php
                if (isset($_SESSION['items'])) {
                    $session_items = $_SESSION['items'];
                    if (empty($session_items)) {
                        unset($_SESSION['items']);
                    }
                ?>
                    <h1 class="text-end" id='total'><b></b></h1><br>
                    <h2 class="text-end" id='generic-total'><b></b></h2>
                    <section class='intro'>
                        <div class='gradient-custom-2 h-100'>
                            <div class='mask d-flex align-items-center h-100'>
                                <div class='container'>
                                    <div class='row justify-content-center'>
                                        <div class='col-12'>
                                            <div class='table-responsive'>
                                                <table class='table table-dark table-bordered mb-0'>
                                                    <thead>
                                                        <tr>
                                                            <th scope='col'>ID</th>
                                                            <th scope='col'>Item Name</th>
                                                            <th scope='col'>Price</th>
                                                            <th scope='col'>Quantity</th>
                                                            <th scope='col'>Subtotal</th>
                                                            <th scope='col'>Type</th>
                                                            <th scope='col'>Remove</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i = 1;
                                                        foreach ($session_items as $key => $item) :
                                                        ?>
                                                            <tr>
                                                                <td scope='col' id='order_id'><?= $i++; ?></td>
                                                                <td scope='col' id='order_item_name'><?= $item['item_name']; ?></td>
                                                                <td scope='col' id='order_price'><?= $item['price']; ?></td>
                                                                <td scope='col'>
                                                                    <div class="input-group qty_box">
                                                                        <input type="hidden" value=<?= $key ?> class="qty_id">
                                                                        <button class="input-group-text decr">-</button>
                                                                        <input type="number" value="<?= $item['quantity']; ?>" class="qty quantityInput" id='order_qty'>
                                                                        <button class="input-group-text incr">+</button>
                                                                    </div>
                                                                </td>
                                                                <td scope='col' id='order_subtotal'><?= $item['price'] * $item['quantity']; ?></td>
                                                                <td scope='col' id='type'><?= $item['type']; ?></td>
                                                                <td scope='col'>
                                                                    <a href="../../actions/user/order_void.php?index=<?= $key; ?>" class="btn btn-danger">🗑️Void</a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <center><button class="btn btn-primary mt-5 checkout" id="checkoutButton">Checkout</button></center>
                <?php
                } else {
                    echo "<h3>No pending order</h3>";
                }
                ?>
            </div>
        </div>
    </div>

    </body>
    <script src="user_ham.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="frontend.js"></script>

    <script>
        document.getElementById('checkoutButton').addEventListener('click', function() {
            var form = document.createElement('form');
            form.action = '../../actions/user/checkout.php';
            form.method = 'post';

            var orderRows = document.querySelectorAll('tbody tr');
            orderRows.forEach(function(row, index) {
                // Retrieve data from elements
                var orderId = row.querySelector('#order_id').textContent;
                var itemName = row.querySelector('#order_item_name').textContent;
                var price = row.querySelector('#order_price').textContent;
                var quantity = row.querySelector('.quantityInput').value; // Assuming class 'quantityInput' is unique within the row
                var subtotal = row.querySelector('#order_subtotal').textContent;
                var type = row.querySelector('#type').textContent;

                // Create hidden input elements for each data item
                form.appendChild(createHiddenInput('items[' + index + '][id]', orderId));
                form.appendChild(createHiddenInput('items[' + index + '][item_name]', itemName));
                form.appendChild(createHiddenInput('items[' + index + '][price]', price));
                form.appendChild(createHiddenInput('items[' + index + '][quantity]', quantity));
                form.appendChild(createHiddenInput('items[' + index + '][subtotal]', subtotal));
                form.appendChild(createHiddenInput('items[' + index + '][type]', type));
            });

            // Get the total from the summary section
            var total = document.getElementById('total').textContent;
            var user = document.getElementById('user').textContent;
            form.appendChild(createHiddenInput('total', total));
            form.appendChild(createHiddenInput('user', user));

            // Append the form to the body and submit it
            document.body.appendChild(form);

            form.submit();


        });

        // Helper function to create hidden input elements
        function createHiddenInput(name, value) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            return input;
        }
    </script>

</html>