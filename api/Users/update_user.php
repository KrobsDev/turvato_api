<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');

include_once('../../config/db_connection.php');
include_once('../../classes/Users.php');


// create an instance of the database class
$db_conn = new Database();
$db = $db_conn->connect();


// create an instance of the user class
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // get the id of the user from the url
    $user->user_id = isset($_GET['uid']) ? $_GET['uid'] : die();

    // get raw data 
    $data = json_decode(file_get_contents("php://input"));

    // set the properties to the posted data for processing
    $user->fname = $data->user_fname;
    $user->mname = $data->user_mname;
    $user->lname = $data->user_lname;
    $user->contact = $data->phone_number;

    // run the function to execute the query
    $result = $user->update_user();

    if ($result) {
        echo "Records updated successfully";
    } else {
        echo "Failed to update records";
    }
} else {
    echo json_encode(array(
        'message' => 'Access Denied'
    ));
}
