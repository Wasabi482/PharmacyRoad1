<?php
@include '../../database/config.php';
session_start();
include '../../actions/admin_midware.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Form</title>
   <link rel="icon" href="../../img/icon copy.png" type="image/x-icon" />
   <link rel="stylesheet" href="../../css/main.css">
   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
   <!-- SweetAlert2 CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
   </link>
   <!-- SweetAlert2 JS -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <!-- Optional Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<body class="bg-dark background" style="background-color:#A41623 !important;">

   <?php
   error_reporting(E_ALL);
ini_set('display_errors', 1);


   use PHPMailer\PHPMailer\PHPMailer;
   use PHPMailer\PHPMailer\Exception;

   require 'phpMailer/src/Exception.php';
   require 'phpMailer/src/PHPMailer.php';
   require 'phpMailer/src/SMTP.php';

   if (isset($_POST['submit'])) {
      $mypic = $_FILES['picture']['name'];
      $temp = $_FILES['picture']['tmp_name'];
      $type = $_FILES['picture']['type'];
      $size = $_FILES['picture']['size'];

      $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
      $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $raw_pass = mysqli_real_escape_string($conn, $_POST['password']);
      $pass = hash('sha256', $_POST['password']);
      $cpass = hash('sha256', $_POST['cpassword']);
      $role_as = $_POST['role_as'];
      if ($role_as = 2) {
         $role = 'Frontend';
      } elseif ($role_as = 3) {
         $role = 'RDU';
      } else {
         $role = 'Not a staff';
      }
      $status = $_POST['status'];

      $select = "SELECT * FROM accounts WHERE firstname = '$firstname' AND lastname = '$lastname' AND email = '$email'";
      $result = mysqli_query($conn, $select);

      if (mysqli_num_rows($result) > 0) {
         $error[] = 'User already exists!';
      } else {
         if ($pass != $cpass) {
            $error[] = 'Passwords do not match!';
         } else {
            if (($type == "image/jpeg" || $type == "image/jpg" || $type == "image/png") && ($size <= 2097152)) {
               move_uploaded_file($temp, "../../img/$mypic");
               $insert = "INSERT INTO accounts(firstname,lastname, email, password, role_as, picture, status) VALUES('$firstname','$lastname','$email','$pass','$role_as', '$mypic','$status')";
               mysqli_query($conn, $insert);

               if ($insert) {
                  $last_insert_id = mysqli_insert_id($conn);

                  // Generate username
                  $username = $last_insert_id . '-' . $lastname . $firstname;

                  // Update the inserted row with the generated username
                  $update_username = "UPDATE accounts SET username = '$username' WHERE id = '$last_insert_id'";
                  mysqli_query($conn, $update_username);

                  $mail = new PHPMailer(true);

                  try {
                     $mail->isSMTP();
                     $mail->Host = 'smtp.gmail.com';
                     $mail->SMTPAuth = true;
                     $mail->Username = 'road1pharmacy@gmail.com';
                     $mail->Password = 'zaetfsdqvnefbvqj';
                     $mail->SMTPSecure = 'ssl';
                     $mail->Port = 465;

                     $mail->setFrom('road1pharmacy@gmail.com', 'Road 1 Pharmacy');
                     $mail->addAddress($email);
                     $mail->isHTML(true);
                     $mail->Subject = 'Welcome to Road 1 Pharmacy';
                     $mail->Body = 'Welcome to Road 1 Pharmacy! 
                            <br> Your account has been created. 
                            <br> You are assigned in the ' . $role . ' department.
                            <br> You can now login to your account.
                            <br>Your username is ' . $username . '.
                            <br> Your password is ' . $raw_pass . ' .
                            <br> Please proceed to this website to login: 
                            <br> https://road1pharmacy.com/pages/index.php';

                     $mail->send();
                  } catch (Exception $e) {
                     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                  }

                  echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
                        <div class='content'>
                            <script>
                            Swal.fire({
                                title: 'Success!',
                                text: 'Added New User',
                                icon: 'success',
                                confirmButtonText: 'Back To Users'
                            }).then(() =>{window.location.href = 'admin_view_users.php';});
                            </script>
                        </div>
                    </main>";
               }
            } else {
               if ($size > 2097152) {
                  $error[] = 'File size must be less than 2MB!';
               } else {
                  $error[] = 'File must be jpg/jpeg/png!';
               }
            }
         }
      }
   };
   ?>

   <div class="section ">
      <div class="container bg-light background ">
         <!-- <div class="image">
            <a href="admin_view_users.php" class="return"><img width="50" height="50" src="https://img.icons8.com/ios/50/008BF8/circled-left--v1.png" alt="circled-left--v1" /></a>
         </div> -->
         <div class="form-section bg-light">
            <div class="logo">
               <img class="logo-image mx-1" src="../../img/IMG_5789__1_-removebg-preview.png" alt="logo">
               <h1 style="color:black;">Road 1 Pharmacy</h1>
            </div>
            <p style="color:black;">Your One Stop Healthcare Pharmacy</p>
            <form ENCTYPE="multipart/form-data" action="" method="post">
               <?php
               if (isset($error) && !empty($error)) {
                  echo '<div class="alert alert-danger">';
                  foreach ($error as $message) {
                     echo "<p>$message</p>";
                  }
                  echo '</div>';
               }
               ?>
               <div class="row">
                  <div class="form-group col-md-6">
                     <input type="text" class="input bg-dark" name="firstname" required placeholder="First Name">
                     <label class="hidden-label" for="username">Firstname</label>
                  </div>
                  <div class="form-group col-md-6">
                     <input type="text" class="input bg-dark" name="lastname" required placeholder="Last Name">
                     <label class="hidden-label" for="username">Last name</label>
                  </div>
                  <!-- <div class="form-group col-md-6">
                     <input type="text" class="input bg-dark" name="username" required placeholder="Username">
                     <label class="hidden-label" for="username">Username</label>
                  </div> -->
                  <div class="form-group col-md-12">
                     <input type="email" class="input bg-dark" name="email" required placeholder="Enter your email">
                     <label class="hidden-label" for="email">Email</label>
                  </div>
                  <div class="form-group col-md-6">
                     <input type="password" class="input bg-dark" id="password" name="password" required placeholder="Password" oninput="validatePassword()">
                     <label class="hidden-label" for="password">Password</label>
                  </div>
                  <script>
                     function validatePassword() {
                        var password = document.getElementById('password').value;

                        // Regular expression to validate password
                        var strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

                        if (strongRegex.test(password)) {
                           document.getElementById('password').setCustomValidity('');
                        } else {
                           document.getElementById('password').setCustomValidity('Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, one digit, and one special character.');
                        }
                     }
                  </script>

                  <div class="form-group col-md-6">
                     <input type="password" class="input bg-dark" name="cpassword" required placeholder="Confirm Password">
                     <label class="hidden-label" for="cpassword">Confirm Password</label>
                  </div>

                  <div class="form-group col-md-6">
                     <input type="file" id="myFile" name="picture" required style="color:black;">
                     <label class="picture" for="picture" style="color:black;">Profile Picture</label>
                  </div>
                  <div class="form-group col-md-6">

                     <select name="role_as" id="role_as" class="input bg-dark">
                        <option value="2">Frontend</option>
                        <option value="3">RDU</option>
                     </select>
                     <label for="role_as">Role</label>
                  </div>
               </div>
               <input type="hidden" name="status" value="out">
               <input type="submit" name="submit" value="Register Now" class="btn btn-primary btn-block">
            </form>
            <div class="other-sign-in">
               <!--<p>Fast sign up with your favorite social profile</p>
               <div class="icons">
                  <a href="https://www.google.com"><i class="bi bi-google h2" style="color: orangered;"></i></a>
                  <a href="https://www.facebook.com"><i class="bi bi-facebook h2" style="color: blue;"></i></a>
                  <a href="https://www.twitter.com"><i class="bi bi-twitter h2" style="color: skyblue;"></i></a>
               </div>-->
            </div>
            </form>
         </div>
      </div>
   </div>
</body>

</html>