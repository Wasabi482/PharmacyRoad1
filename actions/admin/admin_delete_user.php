<?php

include '../../database/config.php'; ?>

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
    if (isset($_POST['yes'])) {
        $id = $_POST['id'];
        $delete = "DELETE FROM accounts WHERE id = '$id'";
        $result = mysqli_query($conn, $delete);
        if (!$result) {
            die('Error: ' . mysqli_error($conn));
        }
        if ($result) {
            echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'Success!',
                   text: 'User Deleted successfully!',
                   icon: 'success',
                   confirmButtonText: 'Back To Users'
               }).then(() =>{window.location.href = '../../pages/admin/admin_view_users.php';});
               </script>
           </div>
       </main>";
        }
    }
    if (isset($_POST['click_delete_user'])) {
        $id = $_POST['id'];


        $data = "SELECT * FROM accounts WHERE id = '$id'";
        $result = mysqli_query($conn, $data);

        if (!$result) {
            die('Error: ' . mysqli_error($conn));
        }
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['id'];

                echo "<form method='post' action='../../actions/admin/admin_delete_user.php'>";

                echo "<h2>" . "Do you want to delete " . "<b>" . $row['username'] . "</b>" . "?</h2>";
                echo "<input type='hidden' name='id' value='$id'><br/>";
                echo "<input type='submit' name='yes' class='btn btn-primary' value='yes'><br/>";
                echo "<a href='../../pages/admin/admin_view_users.php' class='btn btn-danger mt-2'>Cancel</a>";

                echo "</form";
            }
        } else {
            echo "No data found!";
        }
    }

    ?>

</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>