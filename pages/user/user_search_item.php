<?php
include '../../database/config.php';
include '../../actions/session_check.php';
include '../../actions/user_midware.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php
    include 'user_ham.php';

    $curr_user = $_SESSION['role_as'];
    echo "<u>Results: </u>&nbsp";

    if ($curr_user === '2') {

        if (isset($_REQUEST['submit'])) {
            $search = $_GET['search'];
            $terms = explode(" ", $search);
            $data = "SELECT * FROM items WHERE ";

            if (!empty($terms)) {
                $i = 0;
                foreach ($terms as $each) {
                    $i++;
                    if ($i == 1) {
                        $data .= "item_name LIKE '%$each%' ";
                    } else {
                        $data .= "OR item_name LIKE '%$each%' ";
                    }
                }
                $query = mysqli_query($conn, $data);
                if (!$query) {
                    die('Error in SQL query: ' . mysqli_error($conn));
                }
                $num = mysqli_num_rows($query);
                // $num2= mysqli_num_rows($query2);
                if ($num > 0 && $search != "") {

                    echo "  <div class='container'>
                    <div class='row'>";

                    while ($row = mysqli_fetch_assoc($query)) {
                        $item_name = $row['item_name'];
                        $price = $row['price'];

                        $terms2 = explode(" ", $item_name);
                        $data2 = "SELECT * FROM warehouse WHERE ";

                        if (!empty($terms2)) {
                            $si2 = 0;
                            foreach ($terms2 as $each) {
                                $si2++;
                                if ($si2 == 1) {
                                    $data2 .= "item_name LIKE '%$each%' ";
                                } else {
                                    $data2 .= "OR item_name LIKE '%$each%' ";
                                }
                            }
                            $query2 = mysqli_query($conn, $data2);
                        } else {
                            echo "$item_name" . "is empty";
                        }
                        if (!$query2) {
                            die('Error in SQL query: ' . mysqli_error($conn));
                        }
                        $num2 = mysqli_num_rows($query2);
                        if ($num2 > 0 && $item_name != "") {
                            echo "$num2 results for $search<br/>";
                            echo "
                            <h2><center>Item List</center></h2>
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
                                            <th scope='col'>Item Name</th>
                                            <th scope='col'>Price</th>
                                            <th scope='col'>Item Qty</th>
                                            <th scope='col'>Expiry Date</th>
                                            <th scope='col'>Vendor Name</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>";
                            while ($row2 = mysqli_fetch_assoc($query2)) {
                                $warehouse_code = $row2['warehouse_code'];
                                $item_name2 = $row2['item_name'];
                                $item_qty = $row2['item_qty'];
                                $expiry_date = $row2['expiry_date'];
                                $vendor_name = $row2['vendor_name'];

                                echo "<tr>";
                                echo " <td><a href='../../actions/user/user_chose.php?Codes=$warehouse_code&Items=$item_name2&Price=$price&Exp_date=$expiry_date&Vendor_name=$vendor_name'>
                                            <button>";
                                echo $item_name2 . "
                                            </button>
                                            </a>
                                            </td>
                                            ";
                                echo "<td>" . $price . "</td>";
                                echo "<td>" . $item_qty . "</td>";
                                echo "<td>" . $expiry_date . "</td>";
                                echo "<td>" . $vendor_name . "</td>";
                                echo "<input type='hidden' name='warehouse_code' value='$warehouse_code'><br/>";
                                echo "</tr>";
                            }
                            echo "</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br/>
        ";
                        } else {
                            echo "<h4>No Match Found</h4>";
                        }
                    }
                } else {
                    echo "no results found";
                }
            } else {
                echo "wala nakasulat";
            }
        }
    } else {
        if (isset($_REQUEST['submit'])) {
            $search = $_GET['search'];
            $terms = explode(" ", $search);
            $data = "SELECT * FROM warehouse WHERE ";

            if (!empty($terms)) {
                $i = 0;
                foreach ($terms as $each) {
                    $i++;
                    if ($i == 1) {
                        $data .= "item_name LIKE '%$each%' ";
                    } else {
                        $data .= "OR item_name LIKE '%$each%' ";
                    }
                }

                $query = mysqli_query($conn, $data);
                if (!$query) {
                    die('Error in SQL query: ' . mysqli_error($conn));
                }
                $num = mysqli_num_rows($query);
                if ($num > 0 && $search != "") {

                    echo "$num result(s) found for <b>$search</b>!";

                    echo "  <h2><center>Item List</center></h2>
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
                                            <th scope='col'>Warehouse Code</th>
                                            <th scope='col'>Item Name</th>
                                            <th scope='col'>Item Qty</th>
                                            <th scope='col'>Expiry Date</th>
                                            <th scope='col'>Vendor Name</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>";

                    while ($row = mysqli_fetch_assoc($query)) {
                        $warehouse_code = $row['warehouse_code'];
                        $item_name_warehouse = $row['item_name'];
                        $item_qty = $row['item_qty'];
                        $expiry_date = $row['expiry_date'];
                        $vendor_name = $row['vendor_name'];

                        echo "<tr>";
                        echo "<td>" . $warehouse_code . "</td>";
                        echo "<td>" . $item_name_warehouse . "</td>";
                        echo "<td>" . $item_qty . "</td>";
                        echo "<td>" . $expiry_date . "</td>";
                        echo "<td>" . $vendor_name . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br/>
        ";
                } else {
                    echo "no results found";
                }
            } else {
                echo "Please type any Word";
            }
        }
    }


    if ($curr_user === '2') {
        echo "<a href='user_frontend.php'class='btn btn-primary'>Back to Checkout</a>";
    } else {
        echo "<center><a href='user_rdu_view_stock.php' class='btn btn-info'>Back to RDU Stock View</a></center>";
    }




    ?>
    </body>
    <script src="user_ham.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>