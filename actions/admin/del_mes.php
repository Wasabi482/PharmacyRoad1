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
    <title>Document</title>
</head>

<body>
    <?php
    if (isset($_POST['delete_push'])) {
        try {
            $pdo = new PDO(
                "mysql:host=localhost;dbname=capstone;charset=utf8mb4",
                "root",
                ""
            );


            $id = $_POST['id'];

            $stmt = $pdo->prepare("DELETE FROM push_orders WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Redirect back to the admin_view_messages.php after deletion
            // header('Location: admin_view_messages.php');
            // exit;
            echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'Deleted!',
                   text: 'Message deleted successfully',
                   icon: 'info',
                   confirmButtonText: 'Back To Messages'
               }).then(() =>{window.location.href = '../../pages/admin/admin_view_messages.php';});
               </script>
           </div>
       </main>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else if (isset($_POST['delete_report'])) {
        try {
            $pdo = new PDO(
                "mysql:host=localhost;dbname=capstone;charset=utf8mb4",
                "root",
                ""
            );


            $id = $_POST['id'];

            $stmt = $pdo->prepare("DELETE FROM reports WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // // Redirect back to the admin_view_messages.php after deletion
            // header('Location: admin_view_messages.php');
            // exit;
            // echo $id;
            echo "<main role='main' class='col-md-9 ml-sm-auto col-lg-10 px-4 content-wrapper'>
           <div class='content'>
               <script>
               Swal.fire({
                   title: 'Removed!',
                   text: 'Message deleted successfully!!! Make sure to remove it from the Physical Warehouse',
                   icon: 'info',
                   confirmButtonText: 'Back To Messages'
               }).then(() =>{window.location.href = '../../pages/admin/admin_view_messages.php';});
               </script>
           </div>
       </main>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Invalid request.";
    }
    ?>
</body>

</html>