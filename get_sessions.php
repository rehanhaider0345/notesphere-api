<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

// handle preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "db.php";

$user_id = $_GET["user_id"];

$query = "
SELECT session_id, login_time, logout_time, device_info
FROM Login_Session
WHERE user_id = $user_id
ORDER BY login_time DESC
";

$result = mysqli_query($conn, $query);

$sessions = [];

while($row = mysqli_fetch_assoc($result))
{
    $sessions[] = $row;
}

echo json_encode($sessions);
?>
