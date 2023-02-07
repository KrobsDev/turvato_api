<?php
// headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-Type: application/json');


// include necessary files
include_once('../../config/db_connection.php');
include_once('../../classes/Users.php');

// create an instance of the database class
$db_conn = new Database();
// create a database connection
$db = $db_conn->connect();

// create an instance of the user class
$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //  run the function to get all users
    $result = $user->get_users();

    // get row count
    $num = $result->rowCount();

    if ($num > 0) {
        $user_arr['data'] = array();
        // get the rows from the executed query
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            // user item
            $user_Item = array(
                'user_id' => $user_id,
                'user_fname' => $user_fname,
                'user_mname' => $user_mname,
                'user_lname' => $user_lname,
                'phone_number' => $phone_number,
                'user_image' => $user_image,
                'user_email' => $user_email
            );

            // push to "data"

            array_push($user_arr['data'], $user_Item);
        }
        // Turn to JSON
        echo json_encode($user_arr['data']);
    } else {
        echo json_encode(array(
            'message' => 'No users present'
        ));
    }
} else {
    echo json_encode(array(
        'message' => 'Access Denied'
    ));
}
