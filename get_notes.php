<?php

header("Content-Type: application/json");
include "db.php";

$user_id = $_GET['user_id'] ?? null;

if (!$user_id)
{
    echo json_encode([]);
    exit;
}

$user_id = intval($user_id);

$sql = "
SELECT 
    n.note_id,
    n.title,
    n.content,
    n.color,
    n.created_at,
    n.updated_at,
    GROUP_CONCAT(t.tag_name) AS tags
FROM Note n
LEFT JOIN Note_Tag nt ON n.note_id = nt.note_id
LEFT JOIN Tag t ON nt.tag_id = t.tag_id
WHERE n.user_id = $user_id
AND n.deleted_at IS NULL
GROUP BY n.note_id
ORDER BY n.note_id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result)
{
    echo json_encode([
        "error" => mysqli_error($conn)
    ]);
    exit;
}

$notes = [];

while ($row = mysqli_fetch_assoc($result))
{
    $row['tags'] = $row['tags']
        ? explode(',', $row['tags'])
        : [];

    $notes[] = $row;
}

echo json_encode($notes);

?>