<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = $data["username"];
$password = $data["password"];

// check user
$query = "SELECT * FROM User WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0)
{
    $user = mysqli_fetch_assoc($result);
    $user_id = $user["user_id"];

    // create session
    $device = $_SERVER['HTTP_USER_AGENT'];

    mysqli_query($conn,
    "INSERT INTO Login_Session(user_id, login_time, device_info)
    VALUES($user_id, NOW(), '$device')"
    );

    $session_id = mysqli_insert_id($conn);

    echo json_encode([
        "status" => "success",
        "user" => $user,
        "session_id" => $session_id
    ]);
}
else
{
    echo json_encode([
        "status" => "error",
        "message" => "Invalid login"
    ]);
}
?>