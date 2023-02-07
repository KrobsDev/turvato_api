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

    // run the function to get all products
    $results = $product->getAllProducts();

    // count the rows returned
    $count = $results->rowCount();

    // loop through the 
    if ($count > 0) {
        // create array to store the data
        $products['data'] = array();
        // get data from the executed query
        while ($row = $results->fetch(PDO::FETCH_ASSOC)) {
            // extract the row
            extract($row);

            // store values in an array
            $all_products = array(
                'product_id' => $prod_id,
                'product_name' => $product_name,
                'product_desc' => $product_desc,
                'product_cat_id' => $cat_id,
                'product_cat' => $cat_name,
                'product_price' => $product_price,
                'product_type_id' => $type_id,
                'product_type' => $type_name,
                'product_keywords' => $product_keywords,
                'product_image' => $product_image
            );

            // store array in products['data'] array
            array_push($products['data'], $all_products);
        }

        // json encode the array
        echo json_encode($products['data']);
    } else {
        echo json_encode(array(
            'message' => 'No products found'
        ));
    }
} else {
    echo json_encode(array(
        'message' => 'Access Denied'
    ));
}
