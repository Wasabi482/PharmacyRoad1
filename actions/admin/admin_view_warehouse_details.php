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
    if (isset($_POST['click_view_details'])) {
        $item_name = $_POST['item_name'];

        $query = "SELECT * FROM warehouse WHERE item_name = '$item_name'";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            die('Error: ' . mysqli_error($conn));
        }
        if (mysqli_num_rows($result) > 0) {
            echo "
            <center><a href='../../pages/admin/adjust_add_item.php?item_name=$item_name' class='btn btn-primary'>Add new $item_name</a></center>

            <br>
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
                                                    <th scope='col'>Warehouse code</th>
                                                    <th scope='col'>Item Name</th>
                                                    <th scope='col'>QTY</th>
                                                    <th scope='col'>Expiry Date</th>
                                                    <th scope='col'>Batch No</th>
                                                    <th scope='col'>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                    ";
            while ($row = mysqli_fetch_assoc($result)) {
                $warehouse_code = $row['warehouse_code'];
                $item_name = $row['item_name'];
                $item_qty = $row['item_qty'];
                $expiry_date = $row['expiry_date'];
                $batch_no = $row['batch_no'];

                echo "<tr>
                        <td scope='col' class='id'> $warehouse_code</td>
                        <td scope='col' class='amount'> $item_name</td>
                        <td scope='col' class='date'> $item_qty</td>";
                date_default_timezone_set('Asia/Manila');
                $today = date("Y-m-d");
                $diff = strtotime($expiry_date) - strtotime($today);
                $diffInMonths = floor($diff / (30 * 24 * 60 * 60));
                if ($diffInMonths <= 3) {
                    echo "<td scope='col' class='expiry_date' style='color:red;'> $expiry_date</td>";
                } else {
                    echo "<td scope='col' class='expiry_date'> $expiry_date</td>";
                }
                echo "<td scope='col' class='date'> $batch_no</td>";
                echo "<td scope='col'>
                        <a href='../../pages/admin/adjust_item_with.php?warehouse_code=$warehouse_code&item_name=$item_name&item_qty=$item_qty&expiry_date=$expiry_date' class='btn btn-success'>Adjust</a>
                      </td>
                    </tr>";
            }
            echo "
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>";
        }
    }
    ?>

</body>

</html>