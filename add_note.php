<?php
require_once "db.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
{
    http_response_code(200);
    exit();
}

// ================= INPUT =================
$data = json_decode(file_get_contents("php://input"), true);

// ================= VALIDATION =================
if (!isset($data["user_id"]) || !$data["user_id"])
{
    echo json_encode([
        "status" => "error",
        "message" => "Missing user_id"
    ]);
    exit();
}

$user_id = intval($data["user_id"]);

if ($user_id <= 0)
{
    echo json_encode([
        "status" => "error",
        "message" => "Invalid user_id"
    ]);
    exit();
}

$title   = isset($data["title"]) ? mysqli_real_escape_string($conn, $data["title"]) : '';
$content = isset($data["content"]) ? mysqli_real_escape_string($conn, $data["content"]) : '';
$color   = isset($data["color"]) ? mysqli_real_escape_string($conn, $data["color"]) : '';
$tags    = isset($data["tags"]) ? $data["tags"] : [];

// ================= INSERT NOTE =================
$query = "INSERT INTO Note (title, content, color, user_id, created_at, updated_at)
VALUES ('$title', '$content', '$color', $user_id, NOW(), NOW())";

$result = mysqli_query($conn, $query);

if (!$result)
{
    echo json_encode([
        "status" => "error",
        "message" => "Note insert failed",
        "error" => mysqli_error($conn)
    ]);
    exit();
}

$note_id = mysqli_insert_id($conn);

// ================= INSERT TAGS =================
foreach ($tags as $tag_name)
{
    $tag_name = mysqli_real_escape_string($conn, $tag_name);

    if (empty($tag_name)) continue;

    $check = "SELECT tag_id FROM Tag WHERE tag_name='$tag_name'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) == 0)
    {
        mysqli_query($conn, "INSERT INTO Tag(tag_name) VALUES('$tag_name')");
        $tag_id = mysqli_insert_id($conn);
    }
    else
    {
        $row = mysqli_fetch_assoc($result);
        $tag_id = $row["tag_id"];
    }

    mysqli_query($conn, "INSERT INTO Note_Tag(note_id, tag_id)
    VALUES($note_id, $tag_id)");
}

// ================= RESPONSE =================
echo json_encode([
    "status" => "success",
    "note_id" => $note_id
]);
?>
