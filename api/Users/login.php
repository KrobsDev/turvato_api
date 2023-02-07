<?php

// headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: *');
header('Content-Type: application/json');


// bootstrapping
include_once('../../config/db_connection.php');
include_once('../../classes/Users.php');
require('../../vendor/autoload.php');

// firebase JWT package
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();


$db_conn = new Database();
$db = $db_conn->connect();

$user = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    $user->email = $data->user_email;
    $user->password = $data->user_password;

    // get the users details
    $email_check = $user->verify_login();

    $user_arr = $email_check->fetch(PDO::FETCH_ASSOC) ?? '';

    if ($user_arr) {
        // confirm the password
        $confirm_password = password_verify($user->password, $user_arr['user_password']);
        if ($confirm_password) {

            // generate the JWT on successful login
            $payload = [
                'iss' => 'localhost',
                'aud' => 'localhost',
                'exp' => time() + 10000,
            ];

            // secret key
            $secret_key = $_ENV['SECRET_KEY'];

            $jwt = JWT::encode($payload, $secret_key, 'HS256');
            $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

            echo json_encode(array(
                "user_id" => $user_arr['user_id'],
                "user_email" => $user_arr['user_email'],
                "user_role" => $user_arr['user_role'],
                "jwt" => $jwt,
                "message" => "Login successful",
            ));
        } else {
            echo json_encode(array(

                'message' => 'Login failed'
            ));
        }
    } else {
        echo json_encode(array(
            'message' => 'User not found'
        ));
    }
} else {
    echo json_encode(array(
        'message' => 'Access Denied'
    ));
}
