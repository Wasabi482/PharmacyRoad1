<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    </link>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Delete Message</title>
    <link rel="icon" href="../../img/icon copy.png" type="image/x-icon" />
</head>

<body>
    <?php
    if (isset($_POST['submit_push'])) {
        $id = $_POST['id'];
        echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
              <script>
              Swal.fire({
                title: 'Are you sure to delete it?',
                icon: 'warning',
                html: `
                    <form action='del_mes.php' method='post' id='pulloutForm' style='display:inline;'>
                        <input type='hidden' name='id' value='$id'>
                        <button type='submit' class='btn btn-primary' name='delete_push'>Yes</button>
                        <a href='../../pages/admin/admin_view_messages.php' class='btn btn-danger'>No</a>
                    </form>
                `,
                showConfirmButton: false
            });
              </script>
          </div>
       </main>";
    } else if (isset($_POST['submit_report'])) {
        $id = $_POST['id'];
        echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
              <script>
              Swal.fire({
                title: 'Are you sure to Pull out this?',
                icon: 'warning',
                html: `
                    <form action='del_mes.php' method='post' id='pulloutForm' style='display:inline;'>
                        <input type='hidden' name='id' value='$id'>
                        <button type='submit' class='btn btn-primary' name='delete_report'>Yes</button>
                        <a href='../../pages/admin/admin_view_messages.php' class='btn btn-danger'>No</a>
                    </form>
                `,
                showConfirmButton: false
            });
              </script>
          </div>
       </main>";
    }
    ?>
</body>

</html>