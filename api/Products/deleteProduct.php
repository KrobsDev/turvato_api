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
    // get id from url
    $product->prod_id = isset($_GET['pid']) ? $_GET['pid'] : die;

    // run delete function
    $exeFunc = $product->deleteProduct();

    if ($exeFunc) {
        echo json_encode(array(
            "status" => 1,
            "message" => "Product deleted successfully"
        ));
    } else {
        echo json_encode(array(
            "status" => 2,
            "message" => "Failed to delete product"
        ));
    }
} else {
    echo json_encode(array(
        "message" => "Access Denied"
    ));
}
