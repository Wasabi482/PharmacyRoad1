<?php

require_once 'config.php';

// Connect to the database
function connect()
{
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

// Perform a query
function query($sql)
{
    $conn = connect();
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    return $result;
}
