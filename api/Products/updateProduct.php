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

// boolean to check update status
$canUpdate = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // get product id from url
    $product->prod_id = isset($_GET['pid']) ? $_GET['pid'] : die();

    // get values from the request
    $data = json_decode(file_get_contents('php://input'));

    // map data from the request to the product variables
    $product->prod_name = $data->prod_name;
    $product->prod_desc = $data->prod_desc;
    $product->prod_cat = $data->prod_cat;
    $product->prod_price = $data->prod_price ?? 0;
    $product->prod_type = empty($data->prod_price) ? 2 : $data->prod_type;
    $product->prod_keywords = $data->prod_keywords;

    // check for duplicates before adding product
    $chk_duplicates = $product->getOccurrence();

    // count rows
    $count = $chk_duplicates->rowCount();

    // loop through the results
    if ($count > 0) {
        // $prods = array();
        while ($row = $chk_duplicates->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            // store values in an array
            $all_products = array(
                'product_name' => $product_name,
                'product_desc' => $product_desc,
                'product_cat' => $product_cat,
                'product_price' => $product_price,
                'product_type' => $product_type,
                'product_keywords' => $product_keywords,
                'product_image' => $product_image
            );

            // echo json_encode($all_products);

            if ($all_products['product_name'] === trim($data->prod_name)) {
                $canUpdate = false;
                break;
            } else {
                $canUpdate = true;
                continue;
            }
        }
    }

    // execute the update if the conditions are met
    if ($canUpdate) {
        $result = $product->updateProduct();
        echo json_encode(array(
            'status' => 1,
            'message' => 'Product updated successfully'
        ));
    } else {
        echo json_encode(array(
            'message' => 'Product already exists. Consider using a different name'
        ));
    }
} else {
    echo json_encode(array(
        'message' => 'Access Denied'
    ));
}
