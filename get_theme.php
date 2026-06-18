<?php
require_once "db.php";
header("Content-Type: application/json");

$user_id = $_GET["user_id"] ?? null;

if (!$user_id)
{
    echo json_encode(["theme" => "light"]);
    exit;
}

$result = mysqli_query(
    $conn,
    "SELECT theme_mode FROM User_Settings WHERE user_id = $user_id"
);

if ($row = mysqli_fetch_assoc($result))
{
    echo json_encode(["theme" => $row["theme_mode"]]);
}
else
{
    echo json_encode(["theme" => "light"]);
}
?>
