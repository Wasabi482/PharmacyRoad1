<?php
include '../../database/config.php';
session_start();
include '../../actions/admin_midware.php';


if (isset($_GET['item_name'])) {
    $item_name = $_GET['item_name'];

    // Process the received data as required
    // For example, you can display the data or handle the add item logic here

    date_default_timezone_set('Asia/Manila');
    $today =  date("Y-m-d");
    $time = date('H:i:s');

    // Add your add item logic here
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
    <title>Adjust New</title>
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
                        <h5 class="text-center">Add <?php echo $item_name ?></h5>
                        <form action="../../actions/admin/adjust_item.php" method="post">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <h6>Item Name: <b><?php echo $item_name ?></b></h6>
                                    <input type="hidden" class="form-control" name="item_name" value="<?php echo $item_name; ?>" required placeholder="Enter Item Name">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="qty">Quantity:</label>
                                    <input type="number" class="form-control" name="qty" required placeholder="Enter Quantity" min="1">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="exp_date">Expiry Date:</label>
                                    <input type="date" class="form-control" name="expiry_date" required placeholder="Enter Expiry Date">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="batch_no">Batch No</label>
                                    <input type="text" class="form-control" name="batch_no" required placeholder="Batch/Lot no.">
                                </div>
                                <input type="hidden" class="form-control" name="date_received" value="<?php echo $today; ?>" required placeholder="Enter Expiry Date">
                                <input type="hidden" class="form-control" name="time" value="<?php echo $time; ?>" required placeholder="Enter Expiry Date">

                                <div class="form-group col-md-12">
                                    <label for="reason">Reason:</label>
                                    <select class="form-control" name="reason" id="">
                                        <option value="Found from Warehouse">Found from Warehouse</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="vendor_name">Vendor Name:</label>
                                    <select name="vendor_name" class="form-control my-select" id="" required>
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
                            </div>
                            <br>
                            <center>
                                <input type="submit" name="submit_add" value="Add <?php echo $item_name ?>" class="btn btn-primary btn-block text">
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