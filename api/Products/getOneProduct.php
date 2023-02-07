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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // get product name from url
    $product->prod_id = isset($_GET['pid']) ? $_GET['pid'] : die();

    // run the function to get a single product by name
    $result = $product->getOneProduct();


    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(array(
            'message' => 'No product found'
        ));
    }
} else {
    echo json_encode(array(
        'message' => 'Access Denied'
    ));
}
