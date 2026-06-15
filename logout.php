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

$session_id = $data["session_id"];

$query = "
UPDATE Login_Session
SET logout_time = NOW()
WHERE session_id = $session_id
";

mysqli_query($conn, $query);

echo json_encode([
    "status" => "success"
]);
?>