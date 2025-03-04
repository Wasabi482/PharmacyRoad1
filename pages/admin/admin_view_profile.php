<?php

include '../../database/config.php';
session_start();
include '../../actions/admin_midware.php';

if (!isset($_SESSION['admin_name'])) {
  echo "wrong";
}

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Replace 'your_table' with the actual table name and 'your_condition' with the actual condition
$sql = "SELECT id,password, email, picture FROM accounts WHERE username = '" . $_SESSION['admin_name'] . "' LIMIT 1";
$result = $conn->query($sql);

if ($result !== false) {
  if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
      $_SESSION['id'] = $row['id'];
      $_SESSION['password'] = $row['password'];
      $_SESSION['email'] = $row['email'];
      $_SESSION['picture'] = $row['picture'];
    }
  } else {
    echo "0 results";
  }
} else {
  echo "Error: " . $conn->error;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "ham.php"; ?>

  <div class="height-100 bg-light">
    <div class="container">
      <div class="title">
        <h1 class="frontpage-h1"><img src="../../img/IMG_5789__1_-removebg-preview.png" class="logo-image-navbar h1" alt="logo">View Profile</h1>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class='card' style='width: 27rem;'>
            <img src='../../img/<?= $_SESSION['picture']; ?>' class='card-img-top' alt='Profile Picture'>
            <div class='card-body'>
              ID: <p class='card-text id'><?= $_SESSION['id']; ?></p>
              <p class='card-text'><a href="#" class="btn btn-primary edit_password">📝Change Password</a></p>
              <p class='card-text'>Email: <?= $_SESSION['email']; ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade custom-fade" id="editPassModal" tabindex="-1" role="dialog" aria-labelledby="editPassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-modal-center" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPassModalLabel">Edit Credentials</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="edit_form">


          </div>
        </div>
      </div>
    </div>
  </div>



  </body>
  <!-- Load the full jQuery build first -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <!-- Then load Popper.js and Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="admin.js"></script>
  <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html>

<script>
  $(document).ready(function() {
    $('.edit_password').click(function(e) {
      e.preventDefault();
      var id = $(this).closest('div').find('p.id').text();

      // console.log(id);

      $.ajax({
        method: "POST",
        url: "../../actions/admin/admin_edit_password.php",
        data: {
          'click_edit_password': true,
          'id': id,
        },
        success: function(response) {


          $('.edit_form').html(response);
          $('#editPassModal').modal('show');
        }
      });
    });
  });
</script>