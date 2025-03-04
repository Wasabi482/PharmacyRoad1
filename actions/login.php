<?php
@include '../database/config.php';

session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    </link>
    <!-- Bootstrap Bundle JS (includes Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Login</title>
</head>

<body>
    <?php

    if (isset($_POST['submit'])) {

        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $pass = hash('sha256', $_POST['password']);

        $select = "SELECT * FROM accounts WHERE username = '$username' && password = '$pass' ";

        $result = mysqli_query($conn, $select);

        if ($result === false) {
            die('Error: ' . mysqli_error($conn));
        } else {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                $_SESSION['role_as'] = $row['role_as'];
                $_SESSION['authenticated'] = true; // Set session variable on success
                $_SESSION['username'] = $row['username'];
                $_SESSION['name'] = $row['lastname'] . ',' . $row['firstname'];
                $_SESSION['status'] = $row['status'];

                if ($_SESSION['status'] == 'out') {
                    $sessionId = 'in';
                    $updateSession = "UPDATE accounts SET status = '$sessionId' WHERE username ='$username'";
                    mysqli_query($conn, $updateSession);


                    if ($_SESSION['role_as'] == 1) {
                        $_SESSION['admin_name'] = $row['username'];
                        header('location:../pages/admin/admin_page.php');
                        //           echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
                        //        <div class='content'>
                        //            <script>
                        //            Swal.fire({
                        //                title: 'Success!',
                        //                text: 'Welcome ".$_SESSION['admin_name'];
                        //                echo"',
                        //                icon: 'success',
                        //                confirmButtonText: 'Ok'
                        //            }).then(() =>{window.location.href = '../pages/admin/admin_page.php';});
                        //            </script>
                        //        </div>
                        //    </main>";
                    } elseif ($_SESSION['role_as'] == 2) {
                        $_SESSION['user_name'] = $row['username'];
                        header('location:../pages/user/user_frontend.php');
                        //           echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
                        //        <div class='content'>
                        //            <script>
                        //            Swal.fire({
                        //                title: 'Success!',
                        //                text: 'Welcome ".$_SESSION['user_name'];
                        //                echo"',
                        //                icon: 'success',
                        //                confirmButtonText: 'Ok'
                        //            }).then(() =>{window.location.href = '../pages/user/user_frontend.php';});
                        //            </script>
                        //        </div>
                        //    </main>";
                    } elseif ($_SESSION['role_as'] == 3) {
                        $_SESSION['user_name'] = $row['username'];
                        header('location:../pages/user/user_rdu.php');
                        //           echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
                        //        <div class='content'>
                        //            <script>
                        //            Swal.fire({
                        //                title: 'Success!',
                        //                text: 'Welcome ".$_SESSION['user_name'];
                        //                echo"',
                        //                icon: 'success',
                        //                confirmButtonText: 'Ok'
                        //            }).then(() =>{window.location.href = '../pages/user/user_rdu.php';});
                        //            </script>
                        //        </div>
                        //    </main>";
                    } else {
                        $error[] = 'not an admin/staff';
                        echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'Error!',
                   text: 'Oops! " . $error[0];
                        echo "',
                   icon: 'error',
                   confirmButtonText: 'Ok'
               }).then(() =>{window.location.href = '../pages/index.php';});
               </script>
           </div>
       </main>";
                    }
                } else {
                    $error[] = 'User is already logged in on another device!';
                    echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
                    <div class='content'>
                        <script>
                        Swal.fire({
                            title: 'Error!',
                            text: 'Oops! " . $error[0] . "',
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        }).then(() =>{window.location.href = '../pages/index.php';});
                        </script>
                    </div>
                </main>";
                }
            } else {
                $error[] = 'Incorrect username or password!';
                echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'Error!',
                   text: 'Oops! " . $error[0];
                echo "',
                   icon: 'error',
                   confirmButtonText: 'Ok'
               }).then(() =>{window.location.href = '../pages/index.php';});
               </script>
           </div>
       </main>";
            }
        }
    }
    ?>
</body>

</html>