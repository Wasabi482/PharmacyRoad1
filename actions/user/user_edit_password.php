<?php
include '../../database/config.php';
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    </link>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Document</title>
</head>

<body>


    <?php
    if (isset($_POST['save_changes'])) {
        // Retrieve and sanitize form data
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $new_email = mysqli_real_escape_string($conn, $_POST['new_email']);
        $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
        $hash_pass = hash('sha256', $new_pass);


        $update_query = "UPDATE accounts SET email = '$new_email', password = '$hash_pass' WHERE id = '$id'";
        $update_result = mysqli_query($conn, $update_query);

        if ($update_result) {
            echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'Success!',
                   text: 'Credentials updated successfully!',
                   icon: 'success',
                   confirmButtonText: 'Back To Profile'
               }).then(() =>{window.location.href = '../../pages/user/user_view_profile.php';});
               </script>
           </div>
       </main>";
        } else {
            die('Error: ' . mysqli_error($conn));
        }
    }


    if (isset($_POST['click_edit_password'])) {
        $id = $_POST['id'];


        $data = "SELECT * FROM accounts WHERE id = '$id'";
        $result = mysqli_query($conn, $data);

        if (!$result) {
            die('Error: ' . mysqli_error($conn));
        }
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['id'];

                echo "<form method='post' action='../../actions/user/user_edit_password.php'>";
                echo "User";
                echo "<h5>" . "Username: " . $row['username'] . "</h5>";
                echo "<h6>" . "<b>Current Email: </b>" . $row['email'] . "</h6>";
                echo "<label for='new_email'>New Email: </label><br/>
            <input type='email' id='new_email' name='new_email' style='width: 200px; !important;' required ><br />";
                echo "<label for='new_pass'>New Password: </label><br />
            <input type='password' id='new_pass' name='new_pass' style='width: 200px;' required ><br/>";
                echo "<input type='hidden' name='id' value='$id'><br/>";
                echo "<input type='submit' name='save_changes' class='btn btn-primary' value='Save Changes'><br/>
            <a href='../../pages/user/user_view_profile.php' class='btn btn-danger mt-2'>Cancel</a>";

                echo "</form";
            }
        } else {
            echo "No data found!";
        }
    }
    ?>