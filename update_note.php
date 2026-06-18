<?php
require_once "db.php";
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


$data = json_decode(file_get_contents("php://input"), true);

$note_id = $data["note_id"];
$title = $data["title"];
$content = $data["content"];
$color = $data["color"];

$query = "UPDATE Note 
SET title='$title', content='$content', color='$color', updated_at=NOW()
WHERE note_id=$note_id";

mysqli_query($conn, $query);

echo json_encode(["status" => "success"]);
?>
