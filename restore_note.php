<?php
require_once "db.php";
$data = json_decode(file_get_contents("php://input"), true);

$note_id = intval($data["note_id"]);

$query = "UPDATE Note SET deleted_at = NULL WHERE note_id = $note_id";

if (mysqli_query($conn, $query)) {
    echo json_encode(["status" => "restored"]);
}
?>
