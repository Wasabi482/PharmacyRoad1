<?php
include '../../actions/session_check.php';
include '../../actions/admin_midware.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "ham.php"; ?>

    <div class="d-flex flex-row-reverse">
        <form method='GET' action='../../actions/admin/admin_search_item.php'>
            <input type='text' name='search' placeholder="Search Item">
            <input type='submit' class="btn btn-primary" name='item_search' value='Search'>
        </form>
    </div>
    <div class="d-flex flex-row-reverse mt-2">
        <button id='exportExcelBtn' class='btn btn-success mb-2'>Export to Excel</button>
    </div>
    <hr>
    <?php
    include '../../database/config.php';

    // Pagination variables
    $limit = 10; // Number of items per page
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $limit; // Offset for the SQL query

    // Get total number of items
    $total_items = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM items"));

    // Calculate total number of pages
    $total_pages = ceil($total_items / $limit);

    $data = "SELECT * FROM items ORDER BY item_name ASC LIMIT $offset, $limit";
    $result = mysqli_query($conn, $data);
    echo "";
    echo "<div class='row justify-content-between'>
        <div class='col-2'> <h2><img src='../../img/IMG_5789__1_-removebg-preview.png' class='logo-image-navbar h1' alt='logo'>Item List</h2></div>
        <div class='col-2'>".
                //<a href='admin_add_item.php' class='btn btn-primary my-2'>➕Add Item</a>
                "<a href='add_others.php' class='btn btn-primary my-2'>➕Add Item others</a>
                <a href='add_medicine2.php' class='btn btn-primary my-2'>➕Add  medicine </a>
        </div>
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
                                            <th scope ='col'>Classification</th>
                                            <th scope='col'>Vendor Name</th>
                                            <th scope='col'>Actions</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>";

    if (!$result) {
        die('Error in query: ' . mysqli_error($conn));
    }
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
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
            echo "<td>" . "<a href='#' class='btn btn-success edit_item mb-1'>📝Edit</a> &nbsp
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
        echo "Database is Empty";
    }


    // Pagination buttons
    // echo "<div class='row justify-content-center mt-4'>";
    // echo "<nav aria-label='Page navigation example'>";
    // echo "<ul class='pagination'>";

    // // Previous button
    // if ($page > 1) {
    //     echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>Previous</a></li>";
    // }

    // // Page numbers
    // for ($i = 1; $i <= $total_pages; $i++) {
    //     echo "<li class='page-item " . ($page == $i ? 'active' : '') . "'><a class='page-link' href='?page=$i'>$i</a></li>";
    // }

    // // Next button
    // if ($page < $total_pages) {
    //     echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Next</a></li>";
    // }

    // echo "</ul>";
    // echo "</nav>";
    // echo "</div>";

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

    <!-- Your custom script -->
    <script src="admin.js"></script>

</html>

<script>
    $(document).ready(function() {
        $('.edit_item').click(function(e) {
            e.preventDefault();
            var code = $(this).closest('tr').find('.code').text();
            $.ajax({
                method: "POST",
                url: "../../actions/admin/admin_edit_item.php",
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
                url: "../../actions/admin/admin_delete_item.php",
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
    $(document).ready(function() {
        // Export to Excel button click event
        $('#exportExcelBtn').click(function() {
            // Get the table headers
            var tableHeaders = [];
            $('table thead th').each(function(index) {
                // Exclude the "Actions" column (assuming it is the last column)
                if (index !== $('table thead th').length - 1) {
                    tableHeaders.push($(this).text());
                }
            });

            // Get the table data
            var tableData = [tableHeaders]; // Start with the headers
            $('table tbody tr').each(function() {
                var rowData = [];
                $(this).find('td').each(function(index) {
                    // Exclude the "Actions" column (assuming it is the last column)
                    if (index !== $(this).parent().find('td').length - 1) {
                        rowData.push($(this).text());
                    }
                });
                tableData.push(rowData);
            });

            // Create a new Excel workbook
            var workbook = XLSX.utils.book_new();

            // Add the table data to a new worksheet
            var worksheet = XLSX.utils.aoa_to_sheet(tableData);
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Sheet1');

            // Save the workbook as an Excel file
            XLSX.writeFile(workbook, 'items_list.xlsx');
        });

        // Export to PDF button click event

    });
</script>