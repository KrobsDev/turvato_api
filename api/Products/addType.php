<?php

// headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get data from request
    $data = json_decode(file_get_contents('php://input'));

    // map data from request to variables
    $product->type_name = $data->type_name;

    // run the function
    $result = $product->insertType();

    if ($result) {
        echo json_encode(array(
            "message" => "Type added"
        ));
    } else {
        echo json_encode(array(
            "message" => "Failed to add type"
        ));
    }
} else {
    echo json_encode(array(
        'message' => 'Access Denied'
    ));
}
