<?php

// headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');


// bootstrapping
include_once('../../config/db_connection.php');
include_once('../../classes/Users.php');

// create a database connection instance
$db_conn = new Database();
$db = $db_conn->connect();

// instantiate user object
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // get user id from url
    $user->user_id = isset($_GET['uid']) ? $_GET['uid'] : die();

    $user->get_one_user();

    // create array to stucture the output
    $user_arr['data'] = array(
        'user_id' => $user->user_id,
        'user_fname' => $user->fname,
        'user_lname' => $user->lname,
        'user_mname' => $user->mname,
        'phone_number' => $user->contact,
        'user_image' => $user->image,
        'user_email' => $user->email,
        'user_password' => $user->password
    );

    echo json_encode($user_arr);
} else {
    echo json_encode(array(
        'message' => 'Access Denied'
    ));
}
