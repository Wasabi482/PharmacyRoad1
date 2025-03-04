<?php

require_once 'response.php';

// Get user input
$input = isset($_POST['input']) ? $_POST['input'] : '';

// Process user input
$response = handleInput($input);

if (is_array($response)) {
    // Check warehouse stock
    $available_items = checkStock($response);
    if (!empty($available_items)) {
        $sql = "SELECT response FROM then_try";
        $result = query($sql);
        $then_try = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $then_try[] = $row['response'];
        }
        $randomIndex = array_rand($then_try);
        $randomValue = $then_try[$randomIndex];
        echo $randomValue . "\n";

        foreach ($available_items as $key => $item) {
            echo $item . ", ";
        }
    } else {
        $sql = "SELECT response FROM no_alt";
        $result = query($sql);
        $no_alt = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $no_alt[] = $row['response'];
        }
        $randomIndex = array_rand($no_alt);
        $randomValue = $no_alt[$randomIndex];
        echo $randomValue . "\n";
    }
} else {
    echo $response;
}
