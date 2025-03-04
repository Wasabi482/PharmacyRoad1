<?php
include '../../database/config.php';
include '../../actions/session_check.php';
include '../../actions/user_midware.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'user_ham.php';
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        echo "<p id ='user'> " . htmlspecialchars($username) . "</p>";
    } else {
        echo "No session found. Please log in.";
    } ?>
    <div class="height-100 bg-light">
        <div class="container-fluid px-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0"><img src='../../img/IMG_5789__1_-removebg-preview.png' class='logo-image-navbar h1' alt='logo'>
                        View Transactions
                    </h3>
                </div>
                <div class="card-body">
                    <?php
                    $limit = 6; // Number of items per page
                    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
                    $offset = ($page - 1) * $limit; // Offset for the SQL query

                    // Get total number of items
                    $total_items = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM transactions WHERE transact_by = '$username'"));

                    // Calculate total number of pages
                    $total_pages = ceil($total_items / $limit);
                    $trasactions = "SELECT * FROM transactions WHERE transact_by = '$username' ORDER BY id DESC LIMIT $offset, $limit";
                    $result = mysqli_query($conn, $trasactions);
                    if (!$result) {
                        die('Error: ' . mysqli_error($conn));
                    }
                    if (mysqli_num_rows($result) > 0) {
                        echo "
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
                                                            <th scope='col'>Transaction #</th>
                                                            <th scope='col'>Amount</th>
                                                            <th scope='col'>Amount Tendered</th>
                                                            <th scope='col'>Date Of Transaction</th>
                                                            <th scope='col'>Time</th>
                                                            <th scope='col'>Payment Mode</th>
                                                            <th scope='col'>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                    ";
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "    
                                                        <tr>
                                                            <td scope='col' class ='id'> " . $row['id'] . "</td>
                                                            <td scope='col' class='amount'> " . $row['amount'] . "</td>
                                                            <td scope='col' class='amount_tendered'> " . $row['tender_amount'] . "</td>
                                                            <td scope='col' class='date'> " . $row['date_transacted'] . "</td>
                                                            <td scope='col' class = 'time'> " . $row['time_transacted'] . "</td>
                                                            <td scope='col' class='payment_mode'> " . $row['payment_mode'] . "</td>
                                                            <td scope='col' class=''> " . "<a href='#' class='btn btn-info view_details'>🔍View Details</a>" . "&nbsp&nbsp" . "<a href='#' class='btn btn-success reprint'>📄Reprint</a>" . "</td>
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
                    } else {
                        echo "<h3>No Transactions done</h3>";
                    }

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
                </div>
            </div>
        </div>
    </div>
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
    </body>
    </body>
    <!-- Load the full jQuery build first -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Then load Popper.js and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="user_ham.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</html>

<script>
    $(document).ready(function() {
        // Handle the view details button click
        $('.view_details').click(function(e) {
            e.preventDefault();
            var id = $(this).closest('tr').find('td.id').text();
            var amount = $(this).closest('tr').find('td.amount').text();
            var amount_tendered = $(this).closest('tr').find('td.amount_tendered').text();
            var date_transacted = $(this).closest('tr').find('td.date').text();
            var time_transacted = $(this).closest('tr').find('td.time').text();
            var payment_mode = $(this).closest('tr').find('td.payment_mode').text();
            var username = "<?php echo $_SESSION['username']; ?>";

            $.ajax({
                method: "POST",
                url: "../../actions/user/user_view_details.php",
                data: {
                    'click_view_details': true,
                    'id': id,
                    'amount': amount,
                    'amount_tendered': amount_tendered,
                    'date_transacted': date_transacted,
                    'time_transacted': time_transacted,
                    'payment_mode': payment_mode,
                    'username': username,
                },
                success: function(response) {
                    $('.view_form').html(response);
                    $('#viewDetailsModal').modal('show');
                }
            });
        });

        // Handle the reprint button click
        $('.reprint').click(function(e) {
            e.preventDefault();
            var date_transacted = $(this).closest('tr').find('td.date').text();
            var time_transacted = $(this).closest('tr').find('td.time').text();

            $.ajax({
                method: "POST",
                url: "../../actions/user/reprint.php",
                data: {
                    'date_transacted': date_transacted,
                    'time_transacted': time_transacted
                },
                success: function(response) {
                    // Handle the response from reprint.php here
                    // For example, you could display a success message or handle errors
                    // alert(response);
                    Swal.fire({
                        title: 'Reprint!',
                        text: response,
                        icon: 'info',
                        confirmButtonText: 'OK'
                    })
                    //.then((result) => {
                    //     if (result.isConfirmed) {
                    //         window.location.href = '../../pages/user/user_frontend.php';
                    //     }
                    // });
                }
            });
        });

        // Handle modal close button click
        $('.close').click(function() {
            $('#viewDetailsModal').modal('hide');
        });
    });
</script>