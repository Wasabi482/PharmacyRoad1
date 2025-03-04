<?php
include '../../database/config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//middleware
include '../../actions/user_midware.php';
$user_name = $_SESSION['user_name'];

// Initialize $subtotal to null
$subtotal = null;

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['date_received'])) {
    $selected_date = $_POST['date_received'];

    // Prepare the SQL query to calculate the sum of subtotals for the selected date
    $query = "SELECT SUM(total) AS total_subtotal FROM deliver_received WHERE date_received = ?";
    $stmt = mysqli_prepare($conn, $query);

    // Bind the selected date to the statement
    mysqli_stmt_bind_param($stmt, 's', $selected_date);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Bind the result variable
    mysqli_stmt_bind_result($stmt, $subtotal);

    // Fetch the result
    mysqli_stmt_fetch($stmt);

    // Close the statement
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
        <link rel="stylesheet" href="../../css/user.css">
    <?php include "user_ham.php"; ?>
    <!-- Container Main start -->
    <div class="height-100 bg-light">
        <div class="col-md-12 mb-2">
            <div class="card">
                <div class="card-body">
                    <h3><img src='../../img/IMG_5789__1_-removebg-preview.png' class='logo-image-navbar h1' alt='logo'>Delivery Received</h3>
                    <?php
                    date_default_timezone_set('Asia/Manila');
                    $today =  date("Y-m-d");


                    if (isset($_POST['submit'])) {
                        date_default_timezone_set('Asia/Manila');
                        $today =  date("Y-m-d");
                        $startDate = $_POST['startDate'];
                        $endDate = $_POST['endDate'];

                        $total_query = "SELECT SUM(total) as total_amount FROM deliver_received WHERE date_received BETWEEN '$startDate' AND '$endDate' AND received_by ='$user_name'";
                        $total_result = mysqli_query($conn, $total_query);
                        $total_row = mysqli_fetch_assoc($total_result);
                        $capital = $total_row['total_amount'];

                        echo "The Received from " . $startDate . " to " . $endDate . ":" . "<b>" . $capital . "</b>";
                    } else {
                        date_default_timezone_set('Asia/Manila');
                        $today =  date("Y-m-d");

                        $total_query = "SELECT SUM(total) as total_amount FROM deliver_received WHERE date_received = '$today' AND received_by ='$user_name'";
                        $total_result = mysqli_query($conn, $total_query);
                        $total_row = mysqli_fetch_assoc($total_result);
                        $capital = $total_row['total_amount'];
                        if (empty($capital)) {
                            $capital = 0;
                        }
                        echo "Today's Received: " . "<b>" . $capital . "</b>";
                    }
                    ?>
                    <form action="" method="post">

                        <label for="">Start</label>
                        <?php
                        $today =  date("Y-m-d");
                        $minDate = "2024-06-05";
                        ?>
                        <input type="date" id="startDate" name="startDate" placeholder="Start Date (YYYY/MM/DD)" pattern="\d{4}/\d{2}/\d{2}" required min="<?php echo $minDate; ?>" max="<?php echo $today ?>">
                        <label for="">To</label>
                        <?php
                        $today =  date("Y-m-d");
                        $minDate = "2024-06-05";
                        ?>
                        <input type="date" id="endDate" name="endDate" placeholder="End Date (YYYY/MM/DD)" pattern="\d{4}/\d{2}/\d{2}" required  min="<?php echo $minDate; ?>" max="<?php echo $today ?>">
                        <input type="submit" name="submit" class="btn btn-primary"></input>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body bottom" id="content">
                    <p class="card-title text-start fs-3"><img src='../../img/IMG_5789__1_-removebg-preview.png' class='logo-image-navbar h1' alt='logo'><b>Recent Transactions</b></p>
                    <section class="intro">
                        <div class="gradient-custom-2 h-100">
                            <div class="mask d-flex align-items-center h-100">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-light table-bordered mb-0" style="height:46.5vh; width: 100%;">
                                                    <thead>
                                                        <t>
                                                            <th scope="col">System #</th>
                                                            <th scope="col">Transaction #</th>
                                                            <th scope="col">Total</th>
                                                            <th scope="col">Distributor</th>
                                                            <th scope="col">Date Of Transaction</th>
                                                            <th scope="col">Actions</th>
                                                            </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $query = "SELECT * FROM deliver_received WHERE received_by ='$user_name' ORDER BY post_trans_number DESC LIMIT 6 ";
                                                        $result = mysqli_query($conn, $query);
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $row['post_trans_number']; ?></td>
                                                                <td class="id"><?php echo $row['receipt_trans_number']; ?></td>
                                                                <td class="total"><?php echo $row['total']; ?></td>
                                                                <td><?php echo $row['vendor_name']; ?></td>
                                                                <td class="date"><?php echo $row['date_received']; ?></td>
                                                                <td><a href='#' class='btn btn-info view_details'>🔍View Details</a></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                    <!-- table body -->
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
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
    <div class="chatbot">
        <a class="chatbot" onclick="toggleChatbox()">
            <?php
            $randomValue = "Hello there! I'm here to help you monitor stocks.
            Just type: Near Expriy items or Check Expiry + the item name";
            ?>
            <div class="chatbot-circle">
                <img src="../../img/chatbot_icon.gif" alt="">
            </div>
        </a>
    </div>
    <div id="chatbox" style="display:none;">
        <a onclick="closeChatBox()"><i class="fas fa-times" id="chat_close"></i></a>
        <div class="chat-container">
            <h3>Stock Expiry Monitoring Chatbot</h3>
            <div class="chat-messages" id="chat-messages">
                <div class="bot-message"><?php echo $randomValue; ?></div>
            </div>
            <form id="chat-form">
                <input type="text" id="user-input" placeholder="Type your message...">
                <button type="submit">Send</button>
            </form>
        </div>
        <script>
            document.getElementById('chat-form').addEventListener('submit', function(event) {
                event.preventDefault();
                sendMessage();
                document.getElementById("user-input").value = "";
            });

            function sendMessage() {
                var userInput = document.getElementById('user-input').value;
                appendMessage('user', userInput);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', '../../chatbotkuno/index2.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var botResponse = xhr.responseText;
                        appendMessage('bot', botResponse);
                    }
                };
                xhr.send('input=' + userInput);
            }

            function appendMessage(sender, message) {
                var chatMessages = document.getElementById('chat-messages');
                var messageDiv = document.createElement('div');
                messageDiv.className = sender + '-message';
                messageDiv.textContent = message;
                chatMessages.appendChild(messageDiv);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        </script>
    </div>
    <script>
        function toggleChatbox() {
            var chatbox = document.getElementById("chatbox");
            chatbox.style.display = chatbox.style.display === "none" ? "block" : "none";
            // console.log("hi");
        }

        function closeChatBox() {
            var chatbox = document.getElementById("chatbox");
            chatbox.style.display = chatbox.style.display === "none" ? "block" : "none";
        }
    </script>
    <!-- Container Main end -->

    <!-- Main content -->


    <!-- Optional JavaScript -->
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

    </body>

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