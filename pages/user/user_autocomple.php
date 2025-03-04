<?php
// include '../../database/config.php';
// $data = "SELECT item_name, unit_price FROM items";

// $result = mysqli_query($conn, $data);
// $json_array = array();

// while ($row = mysqli_fetch_assoc($result)){

//     $json_array[] = $row; 
// }

// echo json_encode($json_array);



include '../../database/config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../../actions/user_midware.php';

$response = ['success' => false];

if (isset($_POST['item_name'])) {
    $itemName = mysqli_real_escape_string($conn, $_POST['item_name']);

    if ($_SESSION['role_as'] == 2) {
        $query = "SELECT price FROM items WHERE item_name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $itemName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $response = ['success' => true, 'unit_price' => $row['price']];
        }
        $stmt->close();
    }

    if ($_SESSION['role_as'] == 3) {
        $query = "SELECT unit_price FROM items WHERE item_name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $itemName);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $response = ['success' => true, 'unit_price' => $row['unit_price']];
        }
        $stmt->close();
    }
}


echo json_encode($response);
