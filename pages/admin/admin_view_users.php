<?php
include '../../actions/session_check.php';
include '../../actions/admin_midware.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  include "ham.php";
  ?>


  <?php
  include '../../database/config.php';

  // Pagination variables
  $limit = 3; // Maximum users per page
  $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
  $start = ($page - 1) * $limit; // Start index for fetching users

  // Query to fetch users with pagination
  $data = "SELECT * FROM accounts WHERE role_as !=1 LIMIT $start, $limit";
  $result = mysqli_query($conn, $data);

  echo "  <div class='container' style'height:100vh;'>
            <div class='row'>";
  echo "<div class='row justify-content-between'>
        <div class='col-2'> <h4 class='fs-4'><img src='../../img/IMG_5789__1_-removebg-preview.png' class='logo-image-navbar h1' alt='logo'>Users List</h4></div>
        <div class='col-2'><a href='register.php' class='btn btn-primary my-2'>➕Add User</a></div>
    </div>";

  // Check if the query was successful
  if (!$result) {
    die('Error in query: ' . mysqli_error($conn));
  }

  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $id = $row['id'];
      if ($id > 2) {
        $username = $row['username'];
        $email = $row['email'];
        $picture = $row['picture'];
        $role_as = $row['role_as'];
        if ($role_as == 2) {
          $role = 'Frontend';
        } elseif ($role_as == 3) {
          $role = 'RDU';
        } else {
          $role = 'Not a staff';
        }

        echo "

                <div class='col-md-4'>
                    <div class='card' style='height:80vh; width: 18rem;'>
                        <img src='../../img/$picture' class='card-img-top' alt='Profile Picture' style= 'width:100%; height:60%'>
                        <div class='card-body'>
                          <div class='container'>
                              <div class='row'>
                                <div class='col-md-5'>
                                <p class='id' style='font-size:65px;'>  $id</p>
                                </div>
                                <div class='col-md-7'>
                                <p class='card-text '>$username</p>
                                <p class='card-text'> $email</p>
                                <p class='card-text'>$role</p>
                                </div>
                                </div>
                            </div>
                            
                              <center>
                              <a href='#' class='btn btn-secondary edit_user'style='width:49%;'>📝Edit</a>
                              <a href='#' class='btn btn-danger delete_user'style='width:49%;'>🗑️Delete</a>
                              </center>
                        </div>
                    </div>
                </div>";
      } else {
      }
    }
  } else {
    echo "Database is EMPTY";
  }

  echo "            </div>
        </div>";

  // Pagination buttons
  $pagination_query = "SELECT COUNT(*) as total FROM accounts WHERE role_as !=1";
  $pagination_result = mysqli_query($conn, $pagination_query);
  $pagination_row = mysqli_fetch_assoc($pagination_result);
  $total_pages = ceil($pagination_row['total'] / $limit);

  // echo "<div class='row justify-content-center mt-4'>";
  // echo "<nav aria-label='Page navigation example'>";
  // echo "<ul class='pagination'>";

  // // Previous button
  // if ($page > 1) {
  //   echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>Previous</a></li>";
  // }

  // // Page numbers
  // for ($i = 1; $i <= $total_pages; $i++) {
  //   echo "<li class='page-item " . ($page == $i ? 'active' : '') . "'><a class='page-link' href='?page=$i'>$i</a></li>";
  // }

  // // Next button
  // if ($page < $total_pages) {
  //   echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Next</a></li>";
  // }

  // echo "</ul>";
  // echo "</nav>";
  // echo "</div>";

  echo "<div class='row justify-content-center mt-4'>";
  echo "<nav aria-label='Page navigation example'>";
  echo "<ul class='pagination'>";

  if ($page > 1) {
    echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>Previous</a></li>";
  }

  $startPage = max(1, $page - 1);
  $endPage = min($startPage + 2, $total_pages);

  for ($i = $startPage; $i <= $endPage; $i++) {
    echo "<li class='page-item " . ($page == $i ? 'active' : '') . "'><a class='page-link' href='?page=$i'>$i</a></li>";
  }

  if ($page < $total_pages) {
    echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Next</a></li>";
  }

  echo "</ul>";
  echo "</nav>";
  echo "</div>";

  ?>


  <div class="modal fade custom-fade" id="editEmailModal" tabindex="-1" role="dialog" aria-labelledby="editEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-modal-center" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editEmailModalLabel">Edit Credentials</h5>
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

  <div class="modal fade custom-fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-modal-center" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="delete_form">


          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <!-- Load the full jQuery build first -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>


  <!-- Then load Popper.js and Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  <!-- Your custom script -->
  <script src="admin.js"></script>
  <script>
    $(document).ready(function() {
      $('.edit_user').click(function(e) {
        e.preventDefault();
        var id = $(this).closest('div').find('.id').text();
        // console.log(id);
        $.ajax({
          method: "POST",
          url: "../../actions/admin/admin_edit_users.php",
          data: {
            'click_edit_user': true,
            'id': id,
          },
          success: function(response) {
            $('.edit_form').html(response);
            $('#editEmailModal').modal('show');
          }
        });
      });
      $('#editEmailModal').on('hidden.bs.modal', function() {
        location.reload();
      });


      $('.delete_user').click(function(e) {
        e.preventDefault();
        var id = $(this).closest('div').find('.id').text();
        // console.log(id);
        $.ajax({
          method: "POST",
          url: "../../actions/admin/admin_delete_user.php",
          data: {
            'click_delete_user': true,
            'id': id,
          },
          success: function(response) {
            $('.delete_form').html(response);
            $('#deleteUserModal').modal('show');
          }
        });
      });
      $('#deleteUserModal').on('hidden.bs.modal', function() {
        location.reload();
      });
    });
  </script>
  </body>

</html>