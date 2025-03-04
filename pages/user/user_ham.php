<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../../actions/user_midware.php';


include "../../database/config.php";

$usn = $_SESSION['user_name'];
$sql = "SELECT picture FROM accounts WHERE username = '$usn'";
$result = mysqli_query($conn, $sql);
if ($result->num_rows > 0) {   // output data of each row
    $row = $result->fetch_assoc();
    $picture_name = $row["picture"];
}

?>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Simple CSS for side-by-side forms */
    #main {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .form-wrapper {
        width: 100%;
        border: 3px solid black;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .custom-fade {
        width: 70% !important;
        height: 105% !important;
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
    }

    .modal-backdrop.show {
        opacity: 0 !important;
    }
</style>
<title>User Page</title>
<link rel="icon" href="../../img/icon copy.png" type="image/x-icon" />


<!-- SweetAlert2 JS --> <!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
</link>
<!-- Bootstrap Bundle JS (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script> -->

<!-- Custom CSS -->
<style>
    @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap");

    :root {
        --header-height: 3rem;
        --nav-width: 68px;
        --first-color: #4723D9;
        --first-color-light: #AFA5D9;
        --white-color: #F7F6FB;
        --body-font: 'Nunito', sans-serif;
        --normal-font-size: 1rem;
        --z-fixed: 100
    }

    *,
    ::before,
    ::after {
        box-sizing: border-box
    }

    body {
        position: relative;
        margin: var(--header-height) 0 0 0;
        padding: 0 1rem;
        font-family: var(--body-font);
        font-size: var(--normal-font-size);
        transition: .5s
    }

    a {
        text-decoration: none
    }

    .header {
        width: 100%;
        height: var(--header-height);
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1rem;
        background-color: var(--white-color);
        z-index: var(--z-fixed);
        transition: .5s
    }

    .header_toggle {
        color: var(--first-color);
        font-size: 1.5rem;
        cursor: pointer
    }

    .header_img {
        width: 35px;
        height: 35px;
        display: flex;
        justify-content: center;
        border-radius: 50%;
        overflow: hidden
    }

    .header_img img {
        width: 40px
    }

    .l-navbar {
        position: fixed;
        top: 0;
        left: -30%;
        width: var(--nav-width);
        height: 100vh;
        background-color: var(--first-color);
        padding: .5rem 1rem 0 0;
        transition: .5s;
        z-index: var(--z-fixed)
    }

    .nav {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden
    }

    .nav_logo,
    .nav_link {
        display: grid;
        grid-template-columns: max-content max-content;
        align-items: center;
        column-gap: 1rem;
        padding: .5rem 0 .5rem 1.5rem
    }

    .nav_logo {
        margin-bottom: 2rem
    }

    .nav_logo-icon {
        font-size: 1.25rem;
        color: var(--white-color)
    }

    .nav_logo-name {
        color: var(--white-color);
        font-weight: 700
    }

    .nav_link {
        position: relative;
        color: var(--first-color-light);
        margin-bottom: 1.5rem;
        transition: .3s
    }

    .nav_link:hover {
        color: var(--white-color)
    }

    .nav_icon {
        font-size: 1.25rem
    }

    .show {
        left: 0
    }

    .body-pd {
        padding-left: calc(var(--nav-width) + 1rem)
    }

    .active {
        color: var(--white-color)
    }

    .active::before {
        content: '';
        position: absolute;
        left: 0;
        width: 2px;
        height: 32px;
        background-color: var(--white-color)
    }

    .height-100 {
        height: 100vh
    }

    @media screen and (min-width: 768px) {
        body {
            margin: calc(var(--header-height) + 1rem) 0 0 0;
            padding-left: calc(var(--nav-width) + 2rem)
        }

        .header {
            height: calc(var(--header-height) + 1rem);
            padding: 0 2rem 0 calc(var(--nav-width) + 2rem)
        }

        .header_img {
            width: 40px;
            height: 40px
        }

        .header_img img {
            width: 45px
        }

        .l-navbar {
            left: 0;
            padding: 1rem 1rem 0 0
        }

        .show {
            width: calc(var(--nav-width) + 156px)
        }

        .body-pd {
            padding-left: calc(var(--nav-width) + 188px)
        }

        .logo-image-navbar {
            width: 40px;
            height: auto;
            filter: brightness(1.53) contrast(1.5);
            font-size: 20px;
        }
    }
</style>
</head>


<?php
if ($_SESSION['role_as'] == '2') {
    echo "
    <body>
    <body id='body-pd'>
    <header class='header' id='header'>
        <div class='header_toggle'> <i class='bx bx-menu' id='header-toggle' style='color:black !important;'></i> </div>
        <div class='header_time'>
            <h3><b><span id='current_datetime'></span></b></h3>
        </div>
        <div class='header_img'><img src='../../img/$picture_name' alt=''></div>
    </header>
    <div class='l-navbar' id='nav-bar'style = 'background-color:black!important;' >
        <nav class='nav' >
            <div> 
                <a href='user_view_profile.php' class='nav_link' title='Profile'> <i class='bx bx-user nav_icon'></i> <span class='nav_name'>View Profile</span> </a>

                <div class='nav_list'> 
                    <a href='user_page.php' class='nav_link active' title='Dashboard'> <i class='bx bx-grid-alt nav_icon'></i> <span class='nav_name'>Dashboard</span> </a> 
                
                  <a href='user_frontend_view_transaction.php' class='nav_link' title='Transactions'> <i class='bx bx-folder nav_icon'></i> <span class='nav_name'>View Transactions</span> </a> 
            </div>
            </div> <a href='../../actions/logout.php' class='nav_link' title='logOut'> <i class='bx bx-log-out nav_icon'></i> <span class='nav_name'>Sign Out</span> </a>
        </nav>
    </div>
    ";
}
if ($_SESSION['role_as'] == '3') {
    echo "
   <body>
    <body id='body-pd'>
    <header class='header' id='header'>
        <div class='header_toggle'> <i class='bx bx-menu' id='header-toggle' style='color:#FFD700 !important;'></i> </div>
        <div class='header_time'>
            <h3><b><span id='current_datetime'></span></b></h3>
        </div>
        <div class='header_img'><img src='../../img/$picture_name' alt=''></div>
    </header>
    <div class='l-navbar' id='nav-bar' style = 'background-color:yellow !important;' >
        <nav class='nav' >
            <div> 
                <a href='user_view_profile.php' class='nav_link' title='Profile'> <i class='bx bx-user nav_icon'></i> <span class='nav_name'>View Profile</span> </a>

                <div class='nav_list'> 
                    <a href='user_page.php' class='nav_link active' title='Dashboard'> <i class='bx bx-grid-alt nav_icon'></i> <span class='nav_name'>Dashboard</span> </a> 
                
                 <a href='user_rdu_send.php' class='nav_link' title='Push Order'> <i class='bx bx-message-square-detail nav_icon'></i> <span class='nav_name'>Push Order</span> </a> 
                 <a href ='user_rdu_received_item.php' class='nav_link' title='Receive Deliver'> <i class='bx bx-receipt nav_icon'></i> <span class='nav_name'>Receive Deliver</span> </a>
                  
                  <a href='user_rdu_view_stock.php' class='nav_link' title='Stocks'> <i class='bx bx-bar-chart-alt-2 nav_icon'></i> <span class='nav_name'>View Stocks</span> </a> 
            </div>
            </div> <a href='../../actions/logout.php' class='nav_link' title='logOut'> <i class='bx bx-log-out nav_icon'></i> <span class='nav_name'>Sign Out</span> </a>
        </nav>
    </div>
    ";
}
?>
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


<!-- <a href='user_rdu_view_deliver_received.php' class='nav_link'> <i class='bx bx-folder nav_icon'></i> <span class='nav_name'>Deliver Received</span> </a> -->