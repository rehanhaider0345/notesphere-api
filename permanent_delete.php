<?php
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$note_id = intval($data["note_id"]);

$query = "DELETE FROM Note WHERE note_id = $note_id";

if (mysqli_query($conn, $query)) {
    echo json_encode(["status" => "deleted_permanently"]);
}
?>