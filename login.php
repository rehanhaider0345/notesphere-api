<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . "/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["username"]) || !isset($data["password"])) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit();
}

$username = $data["username"];
$password = $data["password"];

$query = "SELECT * FROM User WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    exit();
}

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $user_id = $user["user_id"];

    $device = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

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
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid login"
    ]);
}
