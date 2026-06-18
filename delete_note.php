<?php
require_once "db.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["note_id"]))
{
    echo json_encode([
        "status" => "error",
        "message" => "Missing note_id"
    ]);
    exit;
}

$note_id = intval($data["note_id"]);

/* ===========================
   MOVE TO TRASH (SOFT DELETE)
=========================== */

$sql = "
UPDATE Note 
SET deleted_at = NOW()
WHERE note_id = $note_id
";

$result = mysqli_query($conn, $sql);

if ($result)
{
    echo json_encode([
        "status" => "success",
        "message" => "Note moved to trash"
    ]);
}
else
{
    echo json_encode([
        "status" => "error",
        "message" => "Failed to delete note",
        "sql_error" => mysqli_error($conn)
    ]);
}

?>
