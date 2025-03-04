<?php
include '../../database/config.php';
include '../session_check.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php
    include 'action_ham.php';

    $curr_user = $_SESSION['role_as'];
    if ($curr_user === '1') {
        echo "<center><a href='../../pages/admin/admin_view_warehouse.php' class='btn btn-info'>Back to Stock View</a></center>";
    } else {
        echo "error";
    }
    echo "<u>Results: </u>&nbsp";


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

                echo "  <h2><center>Warehouse List</center></h2>        
        <div class='d-flex flex-row-reverse'>
    <button id='exportExcelBtn' class='btn btn-success mb-2'>Export to Excel</button>
</div>
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
                                            <th scope='col'>Batch No</th>
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
                    if ($num > 0 && $search != "") {

                        echo "$num result(s) found for <b>$search</b>!";

                        echo "  <h2><center>Warehouse List</center></h2>
<div class='d-flex flex-row-reverse'>
    <button id='exportExcelBtn' class='btn btn-success mb-2'>Export to Excel</button>
</div>
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
                                            <th scope='col'>Item Qty</th>
                                            <th scope='col'>Vendor Name</th>
                                            <th scope='col'> Actions </th>

                                            
                                        </tr>
                                    </thead>
                                    <tbody>";

                        while ($row = mysqli_fetch_assoc($query)) {

                            $item_name = $row['item_name'];
                            $item_qty = 0;
                            $vendor_name = "-";

                            echo "<tr>";
                            echo "<td class ='item_name'>" . $item_name . "</td>";
                            echo "<td style= 'color:red;'>" . $item_qty . "</td>";
                            echo "<td>" . $vendor_name . "</td>";
                            echo "<td scope='col' class=''>" . "<a href='#' class='btn btn-success' onclick='sendItemName(this)'>Adjust</a>" . "</td>";
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
                        echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'Error!',
                   text: 'No results found',
                   icon: 'warning',
                   confirmButtonText: 'Back To Warehouse List'
               }).then(() =>{window.location.href = '../../pages/admin/admin_view_warehouse.php';});
               </script>
           </div>
       </main>";
                    }
                } else {
                    echo "Please type any Word";
                }
            }
        } else {
            echo "Please type any Word";
        }
    }

    if (isset($_REQUEST['item_search'])) {
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
            if ($num > 0 && $search != "") {

                echo "$num result(s) found for <b>$search</b>!";

                echo "  <h2><center>Item List</center></h2>
        <div class='d-flex flex-row-reverse'>
    <button id='exportExcelBtn' class='btn btn-success mb-2'>Export to Excel</button>
</div>
       
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
                                            <th scope='col'>Item Code</th>
                                            <th scope='col'>Item Name</th>
                                            <th scope='col'>Unit Price</th>
                                            <th scope='col'>Mark Up(%)</th>
                                            <th scope='col'>Selling Price</th>
                                            <th scope='col'>Type</th>
                                            <th scope='col'>Classification</th>
                                            <th scope='col'>Vendor Name</th>
                                            <th scope='col'>Actions</th>

                                            
                                        </tr>
                                    </thead>
                                    <tbody>";

                while ($row = mysqli_fetch_assoc($query)) {
                    $code = $row['code'];
                    $item_name = $row['item_name'];
                    $unit_price = $row['unit_price'];
                    $mark_up = $row['mark_up'];
                    $price = $row['price'];
                    $type = $row['type'];
                    $classification = $row['classification'];
                    $vendor_name = $row['vendor_name'];
                    echo "<tr>";
                    echo "<td class='code'>" . $code . "</td>";
                    echo "<td>" . $item_name . "</td>";
                    echo "<td>" . $unit_price . "</td>";
                    echo "<td>" . $mark_up . "%" . "</td>";
                    echo "<td>" . $price . "</td>";
                    echo "<td>" . $type . "</td>";
                    echo "<td>" . $classification . "</td>";
                    echo "<td>" . $vendor_name . "</td>";
                    echo "<td>" . "<a href='#' class='btn btn-success edit_item'>📝Edit</a> 
            &nbsp&nbsp 
            <a href='#' class='btn btn-danger delete_item'>🗑️Delete</a>" . "</td>";
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
                echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'Error!',
                   text: 'No results found',
                   icon: 'warning',
                   confirmButtonText: 'Back To Items List'
               }).then(() =>{window.location.href = '../../pages/admin/admin_view_items.php';});
               </script>
           </div>
       </main>";
            }
        } else {
            echo "Please type any Word";
        }


        
    }



    ?>
    <div class="modal fade custom-fade" id="editItemModal" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg custom-modal-center" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel">Edit Item Price</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="edit_form">


                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade custom-fade" id="deleteItemModal" tabindex="-1" role="dialog" aria-labelledby="deleteItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg custom-modal-center" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteItemModalLabel">Delete Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="delete_form">


                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    </body>

    <!-- Load the full jQuery build first -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <!-- Then load Popper.js and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="admin.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</html>

<script>
    $(document).ready(function() {
        // Export to Excel button click event
        $('#exportExcelBtn').click(function() {
            // Get the table data
            var tableData = [];
            $('table tbody tr').each(function() {
                var rowData = [];
                $(this).find('td').each(function() {
                    rowData.push($(this).text());
                });
                tableData.push(rowData);
            });

            // Create a new Excel workbook
            var workbook = XLSX.utils.book_new();

            // Add the table data to a new worksheet
            var worksheet = XLSX.utils.aoa_to_sheet(tableData);
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

            // Save the workbook as an Excel file
            XLSX.writeFile(workbook, 'table_data.xlsx');
        });

        // Export to PDF button click event

    });
</script>
<script>
    $(document).ready(function() {
        $('.edit_item').click(function(e) {
            e.preventDefault();
            var code = $(this).closest('tr').find('.code').text();
            $.ajax({
                method: "POST",
                url: "admin_edit_item.php",
                data: {
                    'click_edit_item': true,
                    'code': code,
                },
                success: function(response) {
                    $('.edit_form').html(response);
                    $('#editItemModal').modal('show');
                }
            });
        });
        $('#editItemModal').on('hidden.bs.modal', function() {
            location.reload();
        });

        $('.delete_item').click(function(e) {
            e.preventDefault();
            var code = $(this).closest('tr').find('.code').text();
            $.ajax({
                method: "POST",
                url: "admin_delete_item.php",
                data: {
                    'click_delete_item': true,
                    'code': code,
                },
                success: function(response) {
                    $('.delete_form').html(response);
                    $('#deleteItemModal').modal('show');
                }
            });
        });
        $('#deleteItemModal').on('hidden.bs.modal', function() {
            location.reload();
        });
    });
</script>
<script>
    function sendItemName(button) {
        var row = button.closest('tr'); // Assuming the button is inside a table row (tr)
        var itemName = row.querySelector('.item_name').innerHTML; // Assuming the item name is in a column with the class "item_name"

        // Redirect to adjust_item.php with the item_name as a parameter
        window.location.href = '../../pages/admin/adjust_item.php?item_name=' + encodeURIComponent(itemName);
    }
</script>