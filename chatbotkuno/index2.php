<?php

require_once 'response2.php';

// Get user input
$input = isset($_POST['input']) ? $_POST['input'] : '';

// Process user input
$response = handleInput($input);

if (is_array($response)) {
    // Check warehouse stock
    $available_items = checkStock($response);
    if (!empty($available_items)) {

        echo "Items: " . "\n";

        foreach ($available_items as $key => $item) {
            echo $item . ", ";
        }
    } else {
        echo "Nothing found";
    }
} else {
    echo $response;
}
