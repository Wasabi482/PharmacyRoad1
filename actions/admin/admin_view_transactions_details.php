<?php
include '../../database/config.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SweetAlert2 CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10"></link>
   <!-- SweetAlert2 JS -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Document</title>
</head>
<body>

<?php
    if(isset($_POST['click_view_details'])){
    $id = $_POST['id'];
    $amount = $_POST['amount'];
    $tendered_amount = $_POST['amount_tendered'];
    $date_transacted = $_POST['date_transacted'];
    $time_transacted = $_POST['time_transacted'];
    $payment_mode = $_POST['payment_mode'];
    $username = $_POST['transact_by'];
    
    $trans_items = "SELECT * FROM transactions_items WHERE order_id = '$id'";
    $result = mysqli_query($conn, $trans_items);
    if(!$result){
        die('Error: '.mysqli_error($conn));
    }
    if(mysqli_num_rows($result) > 0){
        if($username){
            echo"
                    <section class='intro'>
                        <div class='gradient-custom-2 h-100'>
                            <div class='mask d-flex align-items-center h-100'>
                                <div class='container'>
                                    <div class='row justify-content-center'>
                                        <div class='col-12'>
                                            <div class='table-responsive'>
                                            <h2>Transaction #: ".$id."</h2> <br>
                                            <h3>Date: ".$date_transacted .$time_transacted."</h3> <br>
                                            <h3>Transacted By: ".$username."</h3> <br>
                                                <table class='table table-dark table-bordered mb-0'>
                                                    <thead>
                                                        <tr>
                                                            <th scope='col'>Item Name</th>
                                                            <th scope='col'>Price</th>
                                                            <th scope='col'>QTY</th>
                                                            <th scope='col'>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                    ";
                                                        while($row = mysqli_fetch_assoc($result)){
                                                        echo"    
                                                        <tr>
                                                            <td scope='col' class ='id'> ".$row['item_name']."</td>
                                                            <td scope='col' class='amount'> ".$row['price']."</td>
                                                            <td scope='col' class='date'> ".$row['qty']."</td>
                                                            <td scope='col' class='payment_mode'> ".$row['price']*$row['qty']."</td>
                                                        </tr>";                
                     }
                     echo"
                                                    </tbody>
                                                </table>
                                                <h2>Total: ".$amount."</h2><br/>
                                                <h3>Amount Paid: ".$tendered_amount."</h3><br>
                                                <h4>Payment mode: ".$payment_mode."</h4><br>
                                                <h3>Change: ".$tendered_amount - $amount."</h3><br>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>";
        }else{
            echo"
                    <section class='intro'>
                        <div class='gradient-custom-2 h-100'>
                            <div class='mask d-flex align-items-center h-100'>
                                <div class='container'>
                                    <div class='row justify-content-center'>
                                        <div class='col-12'>
                                            <div class='table-responsive'>
                                            <h2>Transaction #: ".$id."</h2> <br>
                                            <h3>Date: ".$date_transacted.$time_transacted."</h3> <br>
                                                <table class='table table-dark table-bordered mb-0'>
                                                    <thead>
                                                        <tr>
                                                            <th scope='col'>Item Name</th>
                                                            <th scope='col'>Price</th>
                                                            <th scope='col'>QTY</th>
                                                            <th scope='col'>Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                    ";
                                                        while($row = mysqli_fetch_assoc($result)){
                                                        echo"    
                                                        <tr>
                                                            <td scope='col' class ='id'> ".$row['item_name']."</td>
                                                            <td scope='col' class='amount'> ".$row['price']."</td>
                                                            <td scope='col' class='date'> ".$row['qty']."</td>
                                                            <td scope='col' class='payment_mode'> ".$row['price']*$row['qty']."</td>
                                                        </tr>";                
                     }
                     echo"
                                                    </tbody>
                                                </table>
                                                <h2>Total: ".$amount."</h2><br/>
                                                <h3>Amount Paid: ".$tendered_amount."</h3><br>
                                                <h4>Payment mode: ".$payment_mode."</h4><br>
                                                <h3>Change: ".$tendered_amount - $amount."</h3><br>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>";
        }
                
} else{
    echo" No Results";
}
}


?>