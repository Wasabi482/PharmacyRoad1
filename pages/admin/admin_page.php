<?php
include '../../database/config.php';
include '../../actions/session_check.php';

//middleware
include '../../actions/admin_midware.php';

if (isset($_POST['submit'])) {
   $startDate = $_POST['startDate'];
   $endDate = $_POST['endDate'];

   $total_query = "SELECT SUM(amount) as total_amount FROM transactions WHERE date_transacted BETWEEN '$startDate' AND '$endDate'";
   $total_result = mysqli_query($conn, $total_query);
   $total_row = mysqli_fetch_assoc($total_result);
   $sales = $total_row['total_amount'];

   $total_query = "SELECT SUM(total) as total_amount FROM deliver_received WHERE date_received BETWEEN '$startDate' AND '$endDate'";
   $total_result = mysqli_query($conn, $total_query);
   $total_row = mysqli_fetch_assoc($total_result);
   $capital = $total_row['total_amount'];

   $total_query = "SELECT SUM(gen_sales) as gen_sum FROM transactions WHERE date_transacted BETWEEN '$startDate' AND '$endDate'";
   $total_result = mysqli_query($conn, $total_query);
   $total_row = mysqli_fetch_assoc($total_result);
   $gen_sales = $total_row['gen_sum'];

   $count_query = "SELECT COUNT(*) AS count FROM transactions WHERE date_transacted BETWEEN '$startDate' AND '$endDate'";
   $count_result = mysqli_query($conn, $count_query);
   $count_row = mysqli_fetch_assoc($count_result);
   $count = $count_row['count'];

   $income =  $sales - $capital;
   $dataPoints = array(
      array("y" => $capital, "label" => "Received"),
      array("y" => $sales, "label" => "Sold"),
      array("y" => $income, "label" => "Income")
   );
   $non_gen_sales = $sales - $gen_sales;
   $dataPoints2 = array(
      array("y" => $gen_sales, "label" => "Generic"),
      array("y" => $non_gen_sales, "label" => "Non Generic")
   );
} else {
   date_default_timezone_set('Asia/Manila');
   $today =  date("Y-m-d");
   $total_query = "SELECT SUM(amount) as total_amount FROM transactions WHERE date_transacted = '$today'";
   $total_result = mysqli_query($conn, $total_query);
   $total_row = mysqli_fetch_assoc($total_result);
   $sales = $total_row['total_amount'];
   if (empty($sales)) {
      $sales = 0;
   }

   $total_query = "SELECT SUM(total) as total_amount FROM deliver_received WHERE date_received = '$today'";
   $total_result = mysqli_query($conn, $total_query);
   $total_row = mysqli_fetch_assoc($total_result);
   $capital = $total_row['total_amount'];
   if (empty($capital)) {
      $capital = 0;
   }

   $total_query = "SELECT SUM(gen_sales) as gen_sum FROM transactions WHERE date_transacted = '$today'";
   $total_result = mysqli_query($conn, $total_query);
   $total_row = mysqli_fetch_assoc($total_result);
   $gen_sales = $total_row['gen_sum'];
   if (empty($gen_sales)) {
      $gen_sales = 0;
   }
   $count_query = "SELECT COUNT(*) AS count FROM transactions WHERE date_transacted = '$today'";
   $count_result = mysqli_query($conn, $count_query);
   $count_row = mysqli_fetch_assoc($count_result);
   $count = $count_row['count'];
   if (empty($count)) {
      $count = 0;
   }

   $income =  $sales - $capital;
   $dataPoints = array(
      array("y" => $capital, "label" => "Received"),
      array("y" => $sales, "label" => "Sold"),
      array("y" => $income, "label" => "Income")
   );
   $non_gen_sales = $sales - $gen_sales;
   $dataPoints2 = array(
      array("y" => $gen_sales, "label" => "Generic"),
      array("y" => $non_gen_sales, "label" => "Non Generic")
   );
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
   <?php include "ham.php"; ?>
   <style>
      /* #dashboard,
      #main_card {
         border: 1px solid #4723D9;
      } */



      .top {
         height: 64.5vh;
      }

      .mid {
         height: 70vh;
      }

      .top,
      .mid,
      .bottom {
         box-shadow: 10px 10px #0D1821;
      }

      .profit {
         /* background-image: url("../../img/profit.gif"); */
         background-color: #FF5964;
         background-repeat: no-repeat;
         background-size: 100% 100%;
         width: 100%;
      }

      .trans {
         background-color: #129490;
         background-repeat: no-repeat;
         background-size: 100% 100%;
         width: 100%;
      }

      .rank {
         background-color: #70B77E;
         background-repeat: no-repeat;
         background-size: 100% 100%;
         width: 100%;
      }
   </style>

   <!-- Container Main start -->
   <?php
   if (isset($_SESSION['username'])) {
      $username = $_SESSION['username'];
   } else {
      echo "No session found. Please log in.";
   }
   //echo $username;
   ?>
   <div class="height-500 bg-light">
      <div class="container-fluid px-4" id="dashboard">
         <div class="card" id="main_card">
            <div class="card-header"><b>
                  <div class="title">
                     <h1 class="frontpage-h1"><img src="../../img/IMG_5789__1_-removebg-preview.png" class="logo-image-navbar h1" alt="logo">DashBoard</h1>
                  </div>
               </b></div>
            <div class="row ">
               <div class="d-flex justify-content-around">
                  <div class="col-md-4 my-1">
                     <div class="card ">
                        <div class="card-body top profit" id="content">
                           <p class="card-title text-start fs-2"><b>Profit</b></p>
                           <p class="card-text text-start fs-1">Your current profit
                              <?php
                              if (isset($_POST['submit'])) {
                                 echo "from " . $startDate . " to " . $endDate . " is: <b> ₱" . number_format($income, 2) . "</b>";
                              } else {
                                 echo "for Today is <b> ₱" . number_format($income, 2) . "</b>";
                              }
                              ?>
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4 my-1">
                     <div class="card">
                        <div class="card-body top trans" id="content" style="height:64.5vh;">
                           <p class=" card-title text-start fs-2"><b>Transaction</b></p>
                           <p class="card-text text-start fs-1">Total transactions
                              <?php
                              if (isset($_POST['submit'])) {
                                 echo "from " . $startDate . " to " . $endDate . " are: <b> " . $count . "</b>";
                              } else {
                                 echo "for Today is <b> " . $count . "</b>";
                              }
                              ?>
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4 my-1">
                     <div class="card">
                        <div class="card-body top rank" id="content">
                           <p class="card-title text-start fs-3"><b>Top Generic Seller</b></p>
                           <p class="card-text text-start fs-4">Top 3 Gen seller
                              <?php
                              if (isset($_POST['submit'])) {
                                 echo "from " . $startDate . " to " . $endDate . " are:";
                              } else {
                                 echo "for Today are ";
                              }
                              ?>
                           </p>
                           <section class="intro">
                              <div class="gradient-custom-2 h-100">
                                 <div class="mask d-flex align-items-center h-100">
                                    <div class="container">
                                       <div class="row justify-content-center">
                                          <div class="col-12">
                                             <div class="table-responsive">
                                                <table class="table table-light table-bordered mb-0" style="height:20.5vh; width: 100%;">
                                                   <thead>
                                                      <tr>
                                                         <th scope="col">Seller</th>
                                                         <th scope="col">Gen Sales</th>
                                                         <th scope="col">Incentives(%)</th>
                                                      </tr>
                                                   </thead>
                                                   <tbody>
                                                      <?php
                                                      if (isset($_POST['submit'])) {
                                                         $user_query = "SELECT DISTINCT transact_by FROM transactions";
                                                         $user_result = mysqli_query($conn, $user_query);
                                                         if ($user_result) {
                                                            $sales_users = array(); // Create an array to store the sales users and their sales
                                                            while ($row = mysqli_fetch_assoc($user_result)) {
                                                               $transacted_by = $row['transact_by'];
                                                               $total_query = "SELECT SUM(gen_sales) as gen_sum FROM transactions WHERE date_transacted BETWEEN '$startDate' AND '$endDate' AND transact_by ='$transacted_by'";
                                                               $total_result = mysqli_query($conn, $total_query);
                                                               $total_row = mysqli_fetch_assoc($total_result);
                                                               $gen_sales_user = $total_row['gen_sum'];
                                                               if (empty($gen_sales_user)) {
                                                                  $gen_sales_user = 0;
                                                               }

                                                               // Store the sales user and their sales in the array
                                                               $sales_users[$transacted_by] = $gen_sales_user;
                                                            }

                                                            // Sort the array in descending order based on sales
                                                            arsort($sales_users);

                                                            // Get the top three sales users
                                                            $top_three_sales_users = array_slice($sales_users, 0, 3);

                                                            // Display the top three sales users
                                                            foreach ($top_three_sales_users as $transacted_by => $gen_sales_user) {
                                                      ?>
                                                               <tr>
                                                                  <td><?php echo $transacted_by; ?></td>
                                                                  <td>₱<?php echo $gen_sales_user; ?></td>
                                                                  <td>₱<?php echo $gen_sales_user * 0.01; ?></td>
                                                               </tr>
                                                            <?php
                                                            }
                                                         } else {
                                                            echo mysqli_error($conn);
                                                         }
                                                      } else {
                                                         $user_query = "SELECT DISTINCT transact_by FROM transactions";
                                                         $user_result = mysqli_query($conn, $user_query);
                                                         if ($user_result) {
                                                            $sales_users = array(); // Create an array to store the sales users and their sales
                                                            while ($row = mysqli_fetch_assoc($user_result)) {
                                                               $transacted_by = $row['transact_by'];
                                                               $total_query = "SELECT SUM(gen_sales) as gen_sum FROM transactions WHERE date_transacted = '$today' AND transact_by ='$transacted_by'";
                                                               $total_result = mysqli_query($conn, $total_query);
                                                               $total_row = mysqli_fetch_assoc($total_result);
                                                               $gen_sales_user = $total_row['gen_sum'];
                                                               if (empty($gen_sales_user)) {
                                                                  $gen_sales_user = 0;
                                                               }
                                                               $sales_users[$transacted_by] = $gen_sales_user;
                                                            }
                                                            arsort($sales_users);

                                                            // Get the top three sales users
                                                            $top_three_sales_users = array_slice($sales_users, 0, 3);

                                                            // Display the top three sales users
                                                            foreach ($top_three_sales_users as $transacted_by => $gen_sales_user) {
                                                            ?>
                                                               <tr>
                                                                  <td><?php echo $transacted_by; ?></td>
                                                                  <td>₱<?php echo $gen_sales_user; ?></td>
                                                                  <td>₱<?php echo $gen_sales_user * 0.01; ?></td>
                                                               </tr>
                                                      <?php
                                                            }
                                                         } else {
                                                            echo mysqli_error($conn);
                                                         }
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
               <div class="d-flex justify-content-around">
                  <div class="col-md-8 my-3">
                     <div class="card">
                        <div class="card-body mid income" id="content">
                           <p class="card-title text-start fs-5"><b>Sales Report</b></p>
                           <p class="class-title text-center fs-3">
                              <?php
                              if (isset($_POST['submit'])) {
                                 echo "The sales from " . $startDate . " to " . $endDate . ":";
                              } else {
                                 echo "Today's Sales";
                              }
                              ?>
                           </p>
                           <form action="" method="post">
                              <label for="">Start Date</label>
                              <?php
                              $sql = "SELECT MIN(date_transacted) AS earliest_date FROM transactions";
                              $result = mysqli_query($conn, $sql);
                              $row = mysqli_fetch_assoc($result);
                              $earliest_date = $row['earliest_date'];


                              ?>
                              <input type="date" id="startDate" name="startDate" placeholder="Start Date (YYYY/MM/DD)" pattern="\d{4}/\d{2}/\d{2}" required min="<?php echo $earliest_date; ?>" max="<?php echo $today ?>">
                              <label for="">End Date</label>
                              <input type="date" id="endDate" name="endDate" placeholder="End Date (YYYY/MM/DD)" pattern="\d{4}/\d{2}/\d{2}" required min="<?php echo $earliest_date; ?>" max="<?php echo $today; ?>">
                              <input type="submit" name="submit" class="btn btn-primary"></input>
                           </form>
                           <div id="chartContainer" style="height: 43vh; width: 95%;"></div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4 my-3">
                     <div class="card">
                        <div class="card-body mid gen" id="content">
                           <p class="card-title text-start fs-4"><b>Generic Sales</b></p>
                           <div id="chartContainer1" style="height: 56vh; width: 100%;"></div>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- <div class="col-md-4 my-1">
                  <div class="card">
                     <div class="card-body top" id="content">
                        <p class="card-title text-start fs-3"><b>Line Graph</b></p>
                        <div id="chartContainer3" style="height: 58vh; width: 100%;"></div>
                     </div>
                  </div>
               </div> -->
               <div class="d-flex justify-content-around">
                  <div class="col-md-8 mb-4">
                     <div class="card">
                        <div class="card-body bottom" id="content">
                           <p class="card-title text-start fs-3"><b>Recent Transactions</b></p>
                           <section class="intro">
                              <div class="gradient-custom-2 h-100">
                                 <div class="mask d-flex align-items-center h-100">
                                    <div class="container">
                                       <div class="row justify-content-center">
                                          <div class="col-12">
                                             <div class="table-responsive">
                                                <table class="table table-light table-bordered mb-0" style="height:46.5vh; width: 100%;">
                                                   <thead>
                                                      <tr>
                                                         <th scope="col">Transaction #</th>
                                                         <th scope="col">Amount</th>
                                                         <th scope="col">Amount Tendered</th>
                                                         <th scope="col">Date Of Transaction</th>
                                                         <th scope="col">Time</th>
                                                      </tr>
                                                   </thead>
                                                   <tbody>
                                                      <?php
                                                      $query = "SELECT * FROM transactions ORDER BY id DESC LIMIT 5";
                                                      $result = mysqli_query($conn, $query);
                                                      while ($row = mysqli_fetch_assoc($result)) {
                                                      ?>
                                                         <tr>
                                                            <td><?php echo $row['id']; ?></td>
                                                            <td>₱<?php echo $row['amount']; ?></td>
                                                            <td>₱<?php echo $row['tender_amount']; ?></td>
                                                            <td><?php echo $row['date_transacted']; ?></td>
                                                            <td><?php echo $row['time_transacted']; ?></td>
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

            </div>
         </div>
      </div>
   </div>
   <?php
   if (isset($_POST['submit'])) {
      $tit = "Sales from " . $startDate . " to " . $endDate;
      $dit = "";
   } else {
      $tit = "Today's Sales";
      $dit = $today;
   }

   ?>
   </body>
   <!-- Load the full jQuery build first -->
   <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha254-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

   <!-- Then load Popper.js and Bootstrap JS -->
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ4hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF84dIHNDz0W1" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjmdVgyd0p3pXB1rRibZUAYoIIy4OrQ4VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
   <script src="admin.js"></script>
   <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+945DzO0rT7abK41JStQIAqVgRVzmbzo5mdXKp4YfRvH+8abtTE1Pi4jizo" crossorigin="anonymous"></script> -->
   <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ4hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF84dIHNDz0W1" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjmdVgyd0p3pXB1rRibZUAYoIIy4OrQ4VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
   <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
   <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
   <script>
      window.onload = function() {
         var chart1 = new CanvasJS.Chart("chartContainer1", {
            animationEnabled: true,
            exportEnabled: true,
            title: {
               text: "<?php echo $tit; ?>"

            },
            subtitles: [{
               text: "<?php echo $dit; ?>"
            }],
            data: [{
               type: "pie",
               showInLegend: "true",
               legendText: "{label}",
               indexLabelFontSize: 16,
               indexLabel: "{label} - #percent%",
               yValueFormatString: "₱#,##0",
               dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
            }]
         });
         chart1.render();

         var chart2 = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            exportEnabled: true,
            theme: "light2",
            title: {
               text: "Sales"
            },
            axisY: {
               title: "Pesos"
            },
            data: [{
               type: "column",
               yValueFormatString: "#,##0.## Pesos",
               dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
         });
         chart2.render();
      }
   </script>

</html>