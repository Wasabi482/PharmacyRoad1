<?php

include '../../database/config.php';?>

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
                   confirmButtonText: 'Back To Users'
               }).then(() =>{window.location.href = '../../pages/admin/admin_view_users.php';});
               </script>
           </div>
       </main>";
    } else {
        die('Error: ' . mysqli_error($conn));
    }
}



if(isset($_POST['click_edit_user'])){
    $id = $_POST['id'];
    
    
    $data = "SELECT * FROM accounts WHERE id = '$id'";
    $result = mysqli_query($conn, $data);

    if(!$result){
        die('Error: '.mysqli_error($conn));
    }
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $id = $row['id'];
             
            echo"<form method='post' action='../../actions/admin/admin_edit_users.php'>";

            echo"<h5>"."Username: ".$row['username']."</h5>";
            echo"<h6>"."<b>Current Email: </b>".$row['email']."</h6>";
            echo"<label for='new_email'>New Email: </label><br/>
            <input type='email' id='new_email' name='new_email' style='width: 200px; !important;' required ><br />";
            echo"<label for='new_pass'>New Password: </label><br />
            <input type='password' id='new_pass' name='new_pass' style='width: 200px;' required ><br/>";
            echo"<input type='hidden' name='id' value='$id'><br/>";
            echo"<input type='submit' name='save_changes' class='btn btn-primary' value='Save Changes'><br/>
            <a href='../../pages/admin/admin_view_users.php' class='btn btn-danger mt-2'>Cancel</a>";
           
            echo"</form";
        }
    }else{
        echo "No data found!";
    }
}
?>

    
</body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</html>