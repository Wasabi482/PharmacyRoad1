<?php
include '../../database/config.php';
include '../../actions/session_check.php';
include '../../actions/user_midware.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "user_ham.php"; ?>
    <?php
    // Check if the query was successful
    $data = "SELECT * FROM deliver_received";
    $result = mysqli_query($conn, $data);
    if (!$result) {
        die('Error in query: ' . mysqli_error($conn));
    }


    if (mysqli_num_rows($result) > 0) {
        echo "<h2><center>Item Received</center></h2>
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

                                            <th scope ='col'>Receipt Trans #</th>
                                            <th scope='col' >Date Received</th>
                                            <th scope='col'>Total</th>
                                            <th scope='col'>Actions</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>";
        while ($row = mysqli_fetch_assoc($result)) {
            $receipt_trans_number = $row['receipt_trans_number'];
            $date_received = $row['date_received'];
            $total = $row['total'];



            echo "<tr>";
            echo "<td class ='id'>" . $receipt_trans_number . "</td>";
            echo "<td class = 'date'>" . $date_received . "</td>";
            echo "<td  class ='total'>" . $total . "</td>";
            echo "<td><a href='#'class='btn btn-info view_details'>🔍View Details</a></td>";
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
        $('.view_details').click(function(e) {
            e.preventDefault();
            var id = $(this).closest('tr').find('td.id').text();
            var total = $(this).closest('tr').find('td.total').text();
            var date_received = $(this).closest('tr').find('td.date').text();

            // console.log(id);
            // console.log(amount);
            // console.log(date_received);
            // console.log(paymnet_mode);

            $.ajax({
                method: "POST",
                url: "../../actions/user/rdu_view_details.php",
                data: {
                    'click_view_details': true,
                    'id': id,
                    'total': total,
                    'date_received': date_received,
                },
                success: function(response) {


                    $('.view_form').html(response);
                    $('#viewDetailsModal').modal('show');
                }
            });
        });
        $('.close').click(function() {
            $('#viewDetailsModal').modal('hide');
        });
    });
</script>