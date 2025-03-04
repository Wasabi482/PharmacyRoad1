<?php
include '../../database/config.php';
include '../../actions/session_check.php';
include '../../actions/admin_midware.php';

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "ham.php"; ?>
    <div class="height-100 bg-light">

        <h1 style="padding-left:10px;"><img src='../../img/IMG_5789__1_-removebg-preview.png' class='logo-image-navbar h1' alt='logo'>Stocks</h1>
        <center>
            <form method='GET' action='../../actions/admin/admin_search_item.php'>

                <input type='text' name='search' placeholder="Search Item">
                <input type='submit' class="btn btn-primary" name='submit' value='Search'>
            </form>
        </center>
        <div class="d-flex flex-row-reverse">
            <button id='exportExcelBtn' class='btn btn-success mb-2'>Export to Excel</button>
        </div>
        <form id='pdfForm' action='../../actions/admin/admin_generate_pdf.php' method='post'>
            <input type='hidden' id='tableContent' name='table_content'>
            <!-- <button type='submit' name='generate_pdf' class='btn btn-primary'>Export to PDF</button> -->
        </form>

        <script>
            // Get table content and submit it to the PHP script
            document.getElementById('pdfForm').addEventListener('submit', function() {
                var tableContent = document.getElementById('myTable').innerHTML;
                document.getElementById('tableContent').value = tableContent;
            });
        </script>
        <hr />

        <div class='container'>

            <div class='row'>

                <?php
                $limit = 10; // Number of items per page
                $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
                $offset = ($page - 1) * $limit; // Offset for the SQL query

                // Get total number of items
                $total_items = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM items"));

                // Calculate total number of pages
                $total_pages = ceil($total_items / $limit);
                // Check if the query was successful
                $get_items = "SELECT item_name FROM items ORDER BY item_name ASC LIMIT $offset, $limit";
                $items_result = mysqli_query($conn, $get_items);
                if (!$items_result) {
                    die('Error in query: ' . mysqli_error($conn));
                }
                echo "<h2><center>Item List</center></h2>
    <section class='intro'>
        <div class='gradient-custom-2 h-100'>
            <div class='mask d-flex align-items-center h-100'>
                <div class='container'>
                    <div class='row justify-content-center'>
                        <div class='col-12'>
                            <div class='table-responsive'>
                                <table class='table table-dark table-bordered mb-0'id='myTable'>
                                    <thead>
                                        <tr>
                                            <th scope='col'>Item Name</th>
                                            <th scope='col'>Item Qty</th>
                                            <th scope='col'>Vendor Name</th>
                                            <th scope='col'>Details </th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>";
                // output data of each row
                while ($item_row = mysqli_fetch_assoc($items_result)) {
                    $item_name = $item_row["item_name"];
                    $data = "SELECT SUM(item_qty) AS total_qty, vendor_name FROM warehouse WHERE item_name = '$item_name' GROUP BY item_name;";
                    $warehouse_result = mysqli_query($conn, $data);

                    if (!$warehouse_result) {
                        die('Error in query: ' . mysqli_error($conn));
                    }

                    $warehouse_row = mysqli_fetch_assoc($warehouse_result);
                    if ($warehouse_row) {
                        // Item exists in the warehouse, get the summed quantity
                        $item_qty = $warehouse_row['total_qty'];
                        $vendor_name = $warehouse_row['vendor_name'];
                    } else {
                        // Item does not exist in the warehouse, display quantity as zero
                        $item_qty = 0;
                        $vendor_name = "-";
                    }

                    echo "<tr>";
                    echo "<td class ='item_name'>" . $item_name . "</td>";
                    if ($item_qty == 0) {
                        echo "<td style= 'color:red;'>" . $item_qty . "</td>";
                        echo "<td>" . $vendor_name . "</td>";
                        echo "<td scope='col' class=''>" . "<a href='#' class='btn btn-success' onclick='sendItemName(this)'>Adjust</a>" . "</td>";
                    } elseif ($item_qty > 0 && $item_qty <= 10) {
                        echo "<td style='color:red;' class='qty'>" . $item_qty . "</td>";
                        echo "<td class='vendor'>" . $vendor_name . "</td>";
                        echo "<td scope='col' class=''> " . "<a href='#' class='btn btn-info view_details mr-1'>View Details</a>" .
                            "</td>";
                    } else {
                        echo "<td class='qty'>" . $item_qty . "</td>";
                        echo "<td class='vendor'>" . $vendor_name . "</td>";
                        echo "<td scope='col' class=''> " . "<a href='#' class='btn btn-info view_details mr-1'>View Details</a>" .
                            "</td>";
                    }
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
                // Pagination buttons
                echo "<div class='row justify-content-center mt-4'>";
                echo "<nav aria-label='Page navigation example'>";
                echo "<ul class='pagination'>";

                if ($page > 1) {
                    echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>Previous</a></li>";
                }

                $startPage = max(1, $page - 1);
                $endPage = min($startPage + 2, $total_pages);

                for ($i = $startPage; $i <= $endPage; $i++) {
                    echo "<li class='page-item " . ($page == $i ? 'active' : '') . "'><a class='page-link' href='?page=$i'>$i</a></li>";
                }

                if ($page < $total_pages) {
                    echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Next</a></li>";
                }

                echo "</ul>";
                echo "</nav>";
                echo "</div>";
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


    <div class="modal fade custom-fade" id="viewDetailsModal" tabindex="-1" role="dialog" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg custom-modal-center" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDetailsModalLabel">View Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="view_form">


                    </div>
                </div>
            </div>
        </div>
    </div>



    </div>
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
        $('.view_details').click(function(e) {
            e.preventDefault();
            var item_name = $(this).closest('tr').find('td.item_name').text();

            // console.log(item_name);

            $.ajax({
                method: "POST",
                url: "../../actions/admin/admin_view_warehouse_details.php",
                data: {
                    'click_view_details': true,
                    'item_name': item_name
                },
                success: function(response) {


                    $('.view_form').html(response);
                    $('#viewDetailsModal').modal('show');
                }
            });
        });
    });
</script>
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
            XLSX.writeFile(workbook, 'warehouse.xlsx');
        });

        // Export to PDF button click event

    });
</script>
<script>
    function sendItemName(button) {
        var row = button.closest('tr'); // Assuming the button is inside a table row (tr)
        var itemName = row.querySelector('.item_name').innerHTML; // Assuming the item name is in a column with the class "item_name"

        // Redirect to adjust_item.php with the item_name as a parameter
        window.location.href = 'adjust_item.php?item_name=' + encodeURIComponent(itemName);
    }
</script>