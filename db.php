<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
{
    http_response_code(200);
    exit();
}

/* ================= DB CONFIG ================= */

$host = "localhost";
$user = "root";
$pass = "";
$db   = "notesphere";

/* ================= CONNECTION ================= */

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error)
{
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed",
        "error" => $conn->connect_error
    ]);
    exit();
}

/* ================= CHARSET ================= */

$conn->set_charset("utf8mb4");

?>