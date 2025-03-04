<?php
include '../../actions/session_check.php';
include '../../actions/admin_midware.php';

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "capstone";

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check for 'unread' status in push_order and reports tables
$unreadQuery = "SELECT COUNT(*) AS unread_count FROM (
    SELECT status FROM push_orders WHERE status = 'unread'
    UNION ALL
    SELECT status FROM reports WHERE status = 'unread'
) AS combined";

$result = $conn->query($unreadQuery);
$row = $result->fetch_assoc();

// Set flag for unread status
$hasUnread = $row['unread_count'] > 0;

$usn = $_SESSION['admin_name'];
$pictureQuery = "SELECT picture FROM accounts WHERE username = '$usn'";
$pictureResult = $conn->query($pictureQuery);
$row = $pictureResult->fetch_assoc();
$picture_name = $row['picture'];


$notifCount = 0;




?>

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<title>Admin Page</title>
<link rel="icon" href="../../img/icon copy.png" type="image/x-icon" />

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
</link>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<link rel="stylesheet" href="../../css/admin_ham.css">
<style>
    .nav2 {
        margin-top: -0.5vh;
    }

    .dropdown {
        /* position: relative; */
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #ED3C5A;
        min-width: 210px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: #AFA5D9;
        text-decoration: none;
        display: block;
    }

    .dropdown-content .nav_name {
        margin-left: 10px;
    }

    .dropdown-content a:hover {
        background-color: #ED3C5A;
    }

    /* .dropdown:hover .dropdown-content {
        display: block;
    } */

    .dropdown-content .nav_link:hover .nav_icon {
        color: var(--white-color);
        text-decoration: underline;
    }

    .dropdown-content .nav_link:hover .nav_name {
        color: var(--white-color);
        text-decoration: underline;
    }

    .notification-bell * {
        background-color: #ED3C5A;
    }
</style>


</head>

<body>

    <body id="body-pd">

        <header class="header" id="header">

            <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle" style="color: #ED3C5A;"></i> </div>
            <div class='header_time'>
                <h5><b><span id='current_datetime'></span></b></h5>
                <script>
                    function updateDateTime() {
                        var now = new Date();
                        var options = {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric',
                            hour: 'numeric',
                            minute: 'numeric',
                            second: 'numeric',
                            hour12: true
                        };
                        var dateTime = now.toLocaleDateString(undefined, options);
                        document.getElementById("current_datetime").innerHTML = dateTime;
                    }
                    setInterval(updateDateTime, 1000);
                </script>
            </div>
            <div class="container-fluid px-2" id="talkbubble" style="display:none;">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Notifications</h6>
                    </div>
                    <hr>
                    <ul class="list-group list-group-flush" style="height:50vh; overflow-y:scroll;">
                        <?php
                        $expiry_query = "SELECT * FROM warehouse";
                        $result = $conn->query($expiry_query);

                        while ($row = $result->fetch_assoc()) {
                            $expiry_date = $row["expiry_date"];
                            $today =  date("Y-m-d");
                            $diff = strtotime($expiry_date) - strtotime($today);
                            $diffInMonths = floor($diff / (30 * 24 * 60 * 60));

                            if ($diffInMonths <= 3) {
                                $notifCount++;
                                $code = $row['warehouse_code'];
                                $item_name = $row['item_name'];
                                $qty = $row['item_qty'];

                                echo "<a href='admin_view_warehouse.php' style='color:black; text-decoration:none;'><li class='list-group-item'> The item <b>" . $item_name . "</b> that has a quantity of <b>" . $qty . "</b> pcs will expire in <b> <span style = color:red;>" . $expiry_date . "</span> </b></li></a>";
                            }
                        }
                        $low_stock_query = "SELECT item_name FROM items";
                        $item_results = $conn->query($low_stock_query);
                        while ($row = $item_results->fetch_assoc()) {
                            $item_name = $row['item_name'];
                            $data = "SELECT SUM(item_qty) AS total_qty, vendor_name FROM warehouse WHERE item_name = '$item_name' GROUP BY item_name;";
                            $warehouse_result = mysqli_query($conn, $data);
                            if (!$warehouse_result) {
                                die('Error in query: ' . mysqli_error($conn));
                            }

                            $warehouse_row = mysqli_fetch_assoc($warehouse_result);
                            if ($warehouse_row) {
                                // Item exists in the warehouse, get the summed quantity
                                $item_qty = $warehouse_row['total_qty'];
                                if ($item_qty <= 20) {
                                    $notifCount++;
                                    echo "<a href='admin_view_warehouse.php' style='color:black; text-decoration:none;'><li class='list-group-item'> The item <b>" . $item_name . "</b> has only <b> <span style = color:red;>" . $item_qty . "</span> </b> pcs only</li></a>";
                                }
                            } else {
                                // Item does not exist in the warehouse, display quantity as zero
                                $item_qty = 0;
                                $notifCount++;
                                echo "<a href='admin_view_warehouse.php' style='color:black; text-decoration:none;'><li class='list-group-item'> The item <b>" . $item_name . "</b> has <b> <span style = color:red;>" . $item_qty . "</span> </b> stocks</li></a>";
                            }
                        }
                        ?>
                    </ul>
                    <hr>
                </div>
            </div>

            <div class="notification-box">
                <span class="notification-count"><?php echo $notifCount; ?></span>
                <button onclick="showAlert()" style="border:none; outline:none">
                    <div class="notification-bell">
                        <span class="bell-top"></span>
                        <span class="bell-middle"></span>
                        <span class="bell-bottom"></span>
                        <span class="bell-rad"></span>
                </button>
            </div>
            </div>

            <script>
                function showAlert() {
                    var div = document.getElementById("talkbubble");
                    if (div.style.display === "none") {
                        div.style.display = "block";
                    } else {
                        div.style.display = "none";
                    }
                }
            </script>
            </div>
            <div class='header_img'>
                <img src='../../img/<?php echo $picture_name; ?>' alt=''>
            </div>

        </header>
        <div class="l-navbar" id="nav-bar" style="background-color:#ED3C5A;">
            <nav class="nav">
                <div class="nav2">
                    <a href="../../pages/admin/admin_view_profile.php" class="nav_logo" title="Profile"> <i class='bx bx-layer nav_logo-icon'></i> <span class="nav_logo-name">Admin</span> </a>

                    <div class="nav_list">
                        <a href="../../pages/admin/admin_page.php" class="nav_link active" title="Dashboard"> <i class='bx bx-grid-alt nav_icon'></i> <span class="nav_name">Dashboard</span> </a>
                        <a href="../../pages/admin/admin_view_users.php" class="nav_link" title="Users"> <i class='bx bx-user nav_icon'></i> <span class="nav_name">Users</span> </a>

                        <a href="../../pages/admin/admin_view_messages.php" class="nav_link" title="Messages">
                            <i class='bx bx-message-square-detail nav_icon <?php if ($hasUnread) echo "text-dark"; ?>'></i>
                            <span class="nav_name">Messages</span>
                        </a>


                        <a href="../../pages/admin/admin_view_items.php" class="nav_link" title="Items"> <i class='bx bx-bookmark nav_icon'></i> <span class="nav_name">Items</span> </a>



                        <div class="dropdown">
                            <a class="dropbtn nav_link" title="Transactions">
                                <i class='bx bx-folder nav_icon'></i>
                                <span class="nav_name">Transactions</span>
                            </a>
                            <div class="dropdown-content" id="myDropdown">
                                <a href="../../pages/admin/admin_view_rdu_transactions.php" class="nav_link" title="Rdu Transactions">
                                    <i class='bx bx-coin-stack nav_icon'></i>
                                    <span class="nav_name">Rdu Transactions</span>
                                </a>
                                <a href="../../pages/admin/admin_view_transactions.php" class="nav_link" title="Offtake Transactions">
                                    <i class='bx bx-cart-alt nav_icon'></i>
                                    <span class="nav_name">Offtake Transactions</span>
                                </a>
                            </div>
                        </div>

                        <script>
                            document.getElementById("myDropdown").style.display = "none";

                            document.querySelector(".dropbtn").addEventListener("click", function() {
                                var dropdownContent = document.getElementById("myDropdown");
                                if (dropdownContent.style.display === "none") {
                                    dropdownContent.style.display = "block";
                                } else {
                                    dropdownContent.style.display = "none";
                                }
                            });
                        </script>

                        <a href="../../pages/admin/admin_view_warehouse.php" class="nav_link" title="Stocks"> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span class="nav_name">Stocks</span> </a>
                    </div>
                </div>
                <a href="../../actions/logout.php" class="nav_link" title="logOut"> <i class='bx bx-log-out nav_icon'></i> <span class="nav_name">SignOut</span> </a>
            </nav>
        </div>