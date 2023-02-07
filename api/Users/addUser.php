<?php

// headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');


// bootstrapping
include_once('../../config/db_connection.php');
include_once('../../classes/Users.php');

// create a database connection instance
$db_conn = new Database();
$db = $db_conn->connect();

// instantiate user object
$user = new User($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //  get the raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $user->fname = $data->user_fname;
    $user->lname = $data->user_lname;
    $user->email = $data->user_email;
    $user->password = $data->user_password;


    // get email of user if exists
    $result = $user->check_duplicates();
    $emailtCheck = $result->fetch(PDO::FETCH_DEFAULT)['user_email'] ?? '';

    // check if the email already exists (compare the current email to an email in the database)
    if (empty($user->email) || empty($user->password) || empty($user->fname) || empty($user->lname)) {
        echo json_encode(array(
            'message' => 'Fields cannot be empty'
        ));
    } else if ($user->email === $emailtCheck) {
        echo json_encode(array(
            'message' => 'User already exists'
        ));
    } else {
        // create new user if the account does not exist
        if ($user->create_user()) {
            echo json_encode(array(
                'message' => 'User created'
            ));
        } else {
            echo json_encode(array(
                'message' => 'User not created'
            ));
        }
    }
} else {
    echo json_encode(array(
        'message' => 'Access Denied'
    ));
}
