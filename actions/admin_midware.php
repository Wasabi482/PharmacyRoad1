<?php
if (!isset($_SESSION['role_as'])) {
    echo '<script>
            alert("You are not authorized.");
            window.location.href = "../index.php";
          </script>';
    exit();
}
