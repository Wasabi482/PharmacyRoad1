<?php
include '../../database/config.php';
session_start();
include '../../actions/user_midware.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../../css/add_order.css">
    <?php include "user_ham.php";
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        echo "<p id ='user'> " . htmlspecialchars($username) . "</p>";
    } else {
        echo "No session found. Please log in.";
    } ?>


    <?php

    if (isset($_POST['submit'])) {
        $receipt_trans_number = $_POST['receipt_trans_number']; // Corrected line
        $item_name = $_POST['item_name'];
        $item_qty = $_POST['item_qty'];
        $expiry_date = $_POST['expiry_date'];
        $vendor_name = $_POST['vendor_name'];
        $date_received = $_POST['date_received'];

        $priceQuery = "SELECT unit_price FROM items WHERE item_name = ?";
        $stmt = $conn->prepare($priceQuery);
        $stmt->bind_param("s", $item_name);
        $stmt->execute();
        $priceResult = $stmt->get_result();
        $unit_price = 0;
        if ($priceResult && $priceRow = $priceResult->fetch_assoc()) {
            $unit_price = $priceRow['unit_price'];
        }
        $stmt->close();

        // Calculate the subtotal
        $subtotal = $unit_price * $item_qty;

        $date_diff = abs(strtotime($expiry_date) - strtotime($date_received));
        $years = floor($date_diff / (365 * 60 * 60 * 24));

        if ($years <= 2) {
            echo "Error: The difference between the expiry date and date received is 2 years or less. Please check your dates.";
        } else {
            // Corrected column name in the query



            // Check if expiry_date and vendor_name already exist in the warehouse database
            $checkQuery = "SELECT * FROM warehouse WHERE item_name='$item_name' AND expiry_date='$expiry_date' AND vendor_name='$vendor_name'";
            $checkResult = mysqli_query($conn, $checkQuery);
            if (mysqli_num_rows($checkResult) > 0) {
                // If they exist, update item_qty
                $updateQuery = "UPDATE warehouse SET item_qty=item_qty+'$item_qty' WHERE expiry_date='$expiry_date' AND vendor_name='$vendor_name'";
                $updateResult = mysqli_query($conn, $updateQuery);
                if ($updateResult) {
                    $date_received;
                    $insertQuery1 = "INSERT INTO deliver_received (receipt_trans_number, item_name, item_qty, expiry_date, vendor_name, date_received, subtotal) VALUES ('$receipt_trans_number', '$item_name', '$item_qty', '$expiry_date', '$vendor_name', '$date_received','$subtotal')";
                    $insertResult1 = mysqli_query($conn, $insertQuery1);
                    if ($insertResult1) {
                        echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'Success!',
                   text: 'Item quantity has been updated successfully in the warehouse database.',
                   icon: 'success',
                   confirmButtonText: 'Ok'
                });
               </script>
               <?php endif; ?>
           </div>
       </main>";
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                // If they don't exist, insert a new row in both databases

                $insertQuery = "INSERT INTO warehouse (item_name, item_qty, expiry_date, vendor_name) VALUES ('$item_name', '$item_qty', '$expiry_date', '$vendor_name')";
                $insertResult = mysqli_query($conn, $insertQuery);
                if ($insertQuery) {
                    $date_received;
                    $insertQuery2 = "INSERT INTO deliver_received (receipt_trans_number,item_name,item_qty, expiry_date,date_received,vendor_name, subtotal) VALUES ('$receipt_trans_number','$item_name','$item_qty','$expiry_date','$date_received','$vendor_name','$subtotal')";
                    $insertResult2 = mysqli_query($conn, $insertQuery2);
                    if ($insertResult2) {
                        echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'Success!',
                   text: 'A new row has been inserted in Warehouse due to NO COMMON Expiry date and Vendor Name.',
                   icon: 'success',
                   confirmButtonText: 'Ok' 
                });
               </script>
               <?php endif; ?>
           </div>
       </main>";
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }
        }
    };

    ?>
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-header">
                <h4 class='mb-0'><img src='../../img/IMG_5789__1_-removebg-preview.png' class='logo-image-navbar h1' alt='logo'>Received Items</h4>
            </div>
            <div class="card-body">
                <div class="row">


                    <form action="../../actions/user/rdu_receive.php" method="post">

                </div>

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label>Item Name</label>
                        <select name="item_name" id="item_name" class='form-select my-select'>
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
                    <div class="col-md-4 mb-3">
                        <label>Unit Price:</label>
                        <b><span id="unit_price_display">0</span></b><br>
                        <input type="number" name="item_qty" required placeholder="Enter Quantity" min="1">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label>Subtotal:</label>
                        <b><span id="subtotal_display">0</span></b>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="date_received">Date Received:</label>
                        <?php
                        $today =  date("Y-m-d");
                        $minDate = "2024-03-06";
                        ?>
                        <input type="date" name="date_received_check" required min="<?php echo $minDate; ?>" max="<?php echo $today ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="expiry_date">Expiry Date:</label>
                        <?php
                        $today =  date("Y-m-d");
                        $maxDate = (new DateTime($today))->modify('+5 years')->format('Y-m-d');
                        $minDate = (new DateTime($today))->modify('+1 year')->format('Y-m-d');
                        ?>
                        <input type="date" name="expiry_date" required min="<?php echo $minDate; ?>" max="<?php echo $maxDate; ?>">

                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="batch_no">Batch/Lot No.</label>
                        <input type="text" name="batch_no" required>
                    </div>
                    <div class="col-md-6 mb-3 text-end">
                        <br />
                        <button type="submit" name="add_item" class="btn btn-primary">➕Add Item</button>
                    </div>

                </div>
                </form>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h4 class='mb-0'><img src='../../img/IMG_5789__1_-removebg-preview.png' class='logo-image-navbar h1' alt='logo'>Lists</h4>
            </div>
            <div class="card-body">
                <?php
                if (isset($_SESSION['items'])) {
                    $session_items = $_SESSION['items'];
                    if (empty($session_items)) {
                        unset($_SESSION['items']);
                    }
                ?>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="invoice_no">Invoice Number:</label><br>
                            <input type="text" name="receipt_trans_number" required placeholder="Enter transaction number"><br>
                            <span id="errorSpan" style="color: red;"></span>
                            <p style="color:gray;">The number/letters on the sales invoice</p>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="vendor_name">Vendor Name:</label><br>
                            <select name="vendor_name" class="my-select" id="" required>
                                <?php
                                $data = "SELECT DISTINCT vendor_name FROM items";
                                $result = mysqli_query($conn, $data);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $vendor_name = $row['vendor_name'];
                                        echo "<option value='$vendor_name'>$vendor_name</option>";
                                    }
                                } else {
                                    echo "<option value=''>No data found!</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="date_received">Date Received:</label><br>
                            <?php
                            $today =  date("Y-m-d");
                            $minDate = "2024-03-06";
                            ?>
                            <input type="date" name="date_received" required min="<?php echo $minDate; ?>" max="<?php echo $today ?>">
                            <span id="errorSpan" style="color: red;"></span>
                        </div>
                    </div>
                    <h1 class="text-end" id='total'><b></b></h1>
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
                                                            <th scope='col'>Unit Price</th>
                                                            <th scope='col'>Quantity</th>
                                                            <th scope='col'>Subtotal</th>
                                                            <th scope='col'>Expiry Date</th>
                                                            <th scope='col'>Batch No</th>
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

                                                                <td scope='col' id='expiry_date'><?= $item['expiry_date']; ?></td>
                                                                <td scope='col' id='batch_no'><?= $item['batch_no']; ?></td>
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
                    <center><button class="btn btn-primary mt-5 receive" id="receiveButton">Receive</button></center>
                <?php
                } else {
                    echo "<h3>No pending Transactions</h3>";
                }
                ?>
            </div>
        </div>





    </div>
    </div>
    </body>


    <script src="user_ham.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="rdu.js"></script>

    <script>
        var receiptTransNumberInput = document.querySelector('input[name="receipt_trans_number"]');
        var vendorNameInput = document.querySelector('select[name="vendor_name"]');
        var dateReceivedInput = document.querySelector('input[name="date_received"]');

        document.getElementById('receiveButton').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent form submission

            var errorSpan = document.getElementById('errorSpan');
            errorSpan.textContent = ''; // Clear any previous error message


            if (receiptTransNumberInput.value === '' || vendorNameInput.value === '' || dateReceivedInput.value === '') {
                errorSpan.textContent = 'Please fill in all the required fields.';
                return;
            }
            var form = document.createElement('form');
            form.action = '../../actions/user/receive_item.php';
            form.method = 'post';

            var orderRows = document.querySelectorAll('tbody tr');
            orderRows.forEach(function(row, index) {
                // Retrieve data from elements
                var orderId = row.querySelector('#order_id').textContent;
                var itemName = row.querySelector('#order_item_name').textContent;
                var price = row.querySelector('#order_price').textContent;
                var quantity = row.querySelector('.quantityInput').value; // Assuming class 'quantityInput' is unique within the row
                var subtotal = row.querySelector('#order_subtotal').textContent;
                var expiryDate = row.querySelector('#expiry_date').textContent;
                var batchNo = row.querySelector('#batch_no').textContent;

                // Create hidden input elements for each data item
                form.appendChild(createHiddenInput('items[' + index + '][id]', orderId));
                form.appendChild(createHiddenInput('items[' + index + '][item_name]', itemName));
                form.appendChild(createHiddenInput('items[' + index + '][price]', price));
                form.appendChild(createHiddenInput('items[' + index + '][quantity]', quantity));
                form.appendChild(createHiddenInput('items[' + index + '][subtotal]', subtotal));
                form.appendChild(createHiddenInput('items[' + index + '][expiry_date]', expiryDate));
                form.appendChild(createHiddenInput('items[' + index + '][batch_no]', batchNo));
            });

            // Get the total from the summary section
            var total = document.getElementById('total').textContent;
            var user = document.getElementById('user').textContent;
            form.appendChild(createHiddenInput('total', total));
            form.appendChild(createHiddenInput('user', user));
            form.appendChild(createHiddenInput('receipt_trans_number', receiptTransNumberInput.value));
            form.appendChild(createHiddenInput('vendor_name', vendorNameInput.value));
            form.appendChild(createHiddenInput('date_received', dateReceivedInput.value));

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