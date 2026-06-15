<?php
include "db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data["user_id"] ?? null;
$theme = $data["theme"] ?? null;

if (!$user_id || !$theme)
{
    echo json_encode(["status" => "error", "message" => "Missing data"]);
    exit;
}

// check if record exists
$check = mysqli_query($conn, "SELECT * FROM User_Settings WHERE user_id = $user_id");

if (mysqli_num_rows($check) > 0)
{
    // update
    mysqli_query($conn,
        "UPDATE User_Settings 
         SET theme_mode = '$theme' 
         WHERE user_id = $user_id"
    );
}
else
{
    // insert
    mysqli_query($conn,
        "INSERT INTO User_Settings (user_id, theme_mode)
         VALUES ($user_id, '$theme')"
    );
}

echo json_encode(["status" => "success"]);
?>