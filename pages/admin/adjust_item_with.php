<?php
include '../../database/config.php';
session_start();
include '../../actions/admin_midware.php';

if (isset($_GET['warehouse_code']) && isset($_GET['item_name']) && isset($_GET['item_qty']) && isset($_GET['expiry_date'])) {
    $warehouse_code = $_GET['warehouse_code'];
    $item_name = $_GET['item_name'];
    $item_qty = $_GET['item_qty'];
    $expiry_date = $_GET['expiry_date'];
    date_default_timezone_set('Asia/Manila');
    $today =  date("Y-m-d");
    $time = date('H:i:s');

    // Process the received data as required
    // For example, you can display the data or handle the adjustment logic here
    // Add your adjustment logic here
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <title>Adjust Item</title>
    <link rel="icon" href="../../img/icon copy.png" type="image/x-icon" />
    <style>
        body {
            background-color: #ED3C5A;
        }

        .card {
            background-color: #D6FF79;

        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card mt-5">
                    <div class="card-body">
                        <h5 class="text-center">Adjust <?php echo $item_name ?></h5>
                        <form action="../../actions/admin/adjust_item.php" method="post">
                            <div class="row">
                                <input type="hidden" name="id" value="<?php echo $warehouse_code;   ?>">
                                <div class="form-group col-md-12">
                                    <h6>Item Name: <b><?php echo $item_name ?></b></h6>
                                    <input type="hidden" class="form-control" name="item_name" value="<?php echo $item_name; ?>" required placeholder="Enter Item Name">
                                </div>
                                <div class="form-group col-md-12">
                                    <h6 for="qty">Current Quantity: <?php echo $item_qty; ?></h6>
                                    <input type="hidden" class="form-control" name="curr_qty" required placeholder="Enter Quantity" min="1" value="<?php echo $item_qty; ?>">
                                    <select name="process" id="">
                                        <option value="Add">+ ADD</option>
                                        <option value="Sub">- Subtract</option>
                                    </select>
                                    <input type="number" class="form-control" name="qty" required placeholder="Enter Quantity" min="1">

                                </div>
                                <input type="hidden" class="form-control" name="date" value="<?php echo $today; ?>" required placeholder="Enter Expiry Date">
                                <input type="hidden" class="form-control" name="expiry_date" value="<?php echo $expiry_date; ?>" required placeholder="Enter Expiry Date">
                                <input type="hidden" class="form-control" name="time" value="<?php echo $time; ?>" required placeholder="Enter Expiry Date">

                            </div>
                            <br>
                            <center>
                                <input type="submit" name="submit_edit" value="Adjust <?php echo $item_name ?>" class="btn btn-primary btn-block text">
                                <a href="admin_view_warehouse.php" class="btn btn-danger btn-block text">Cancel</a>
                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.my-select').select2();
    });
</script>

</html>