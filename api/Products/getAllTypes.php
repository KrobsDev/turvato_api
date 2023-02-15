<?php

// headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');


// bootstrapping
include_once('../../config/db_connection.php');
include_once('../../classes/Products.php');

// create a database connection instance
$db_conn = new Database();
$db = $db_conn->connect();

// instantiate product object
$product = new Product($db);


// check request method
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // run the function to get all categories
    $categories = $product->getAllTypes();

    echo json_encode($categories);
} else {
    echo json_encode(array(
        "message" => 'Access Denied'
    ));
}
