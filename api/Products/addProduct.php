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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // get data from the request
    $data = json_decode(file_get_contents('php://input'));

    // map data from request to product variables
    $product->prod_name = $data->prod_name;
    $product->prod_desc = $data->prod_desc;
    $product->prod_cat = $data->prod_cat;
    $product->prod_price = $data->prod_price;
    $product->prod_type = $data->prod_type;
    $product->prod_keywords = $data->prod_keywords;
    $product->prod_image = $data->prod_image;


    // check for duplicates before adding product
    $chk_duplicates = $product->getDuplicates();

    if ($chk_duplicates['product_name'] === $data->prod_name) {
        echo json_encode(array(
            'message' => 'Product already exists'
        ));
    } else {
        // run the query
        $result = $product->insertProduct();

        if ($result) {
            echo json_encode(array(
                'message' => 'Product added successfully'
            ));
        } else {
            echo json_encode(array(
                'message' => 'Failed'
            ));
        }
    }
} else {
    echo json_encode(array(
        'message' => 'Access Denied'
    ));
}
