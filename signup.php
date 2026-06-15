<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
{
    http_response_code(200);
    exit();
}

include "db.php";

$json = file_get_contents("php://input");
$data = json_decode($json, true);

if (!$data)
{
    echo json_encode([
        "status" => "error",
        "message" => "Invalid JSON"
    ]);
    exit();
}

$username = $data["username"];
$email = $data["email"];
$password = $data["password"];

// check existing user
$check = mysqli_query($conn, "SELECT * FROM User WHERE username='$username' OR email='$email'");

if (mysqli_num_rows($check) > 0)
{
    echo json_encode([
        "status" => "error",
        "message" => "User already exists"
    ]);
    exit();
}

// insert user
mysqli_query($conn, "INSERT INTO User(username,email,password)
VALUES('$username','$email','$password')");

echo json_encode([
    "status" => "success"
]);