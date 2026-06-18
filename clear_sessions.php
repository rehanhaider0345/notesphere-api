<?php
require_once "db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$user_id = isset($data["user_id"]) ? $data["user_id"] : null;

if (!$user_id)
{
    echo json_encode([
        "status" => "error",
        "message" => "user_id missing"
    ]);
    exit;
}

$query = "DELETE FROM Login_Session WHERE user_id = $user_id";

$result = mysqli_query($conn, $query);

if ($result)
{
    echo json_encode([
        "status" => "success"
    ]);
}
else
{
    echo json_encode([
        "status" => "error",
        "message" => mysqli_error($conn)
    ]);
}
?>
