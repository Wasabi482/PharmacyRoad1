<?php
include '../../database/config.php';
include '../../actions/session_check.php';
include '../../actions/user_midware.php';

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "user_ham.php"; ?>
    <div class="height-100 bg-light">

        <h1><img src='../../img/IMG_5789__1_-removebg-preview.png' class='logo-image-navbar h1' alt='logo'>Stocks:</h1>
        <center>
            <form method='GET' action='user_search_item.php'>

                  <input type='text' name='search' placeholder="Search Item">
                <input type='submit' class="btn btn-primary" name='submit' value='Search'>
            </form>
        </center>
        <hr />

        <div class='container'>
            <div class='row'>

                <?php
                // Check if the query was successful
                $data = "SELECT * FROM warehouse";
                $result = mysqli_query($conn, $data);
                if (!$result) {
                    die('Error in query: ' . mysqli_error($conn));
                }


                if (mysqli_num_rows($result) > 0) {
                    echo "<h2><center>Item List</center></h2>
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
                                            <th scope='col'>Batch No.</th>
                                            <th scope='col'>Vendor Name</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        $warehouse_code = $row['warehouse_code'];
                        $item_name_warehouse = $row['item_name'];
                        $item_qty = $row['item_qty'];
                        $expiry_date = $row['expiry_date'];
                        $vendor_name = $row['vendor_name'];
                        $batch_no = $row['batch_no'];



                        echo "<tr>";
                        echo "<td>" . $warehouse_code . "</td>";
                        echo "<td>" . $item_name_warehouse . "</td>";
                        echo "<td>" . $item_qty . "</td>";
                        echo "<td>" . $expiry_date . "</td>";
                        echo "<td>" . $batch_no . "</td>";
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
                    echo "Database is EMPTY";
                }


                ?>

                <?php


                ?>
                </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </section>
    <br />





    </div>
    </body>
    <script src="user_ham.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>