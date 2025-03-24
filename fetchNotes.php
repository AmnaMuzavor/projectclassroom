<?php
include 'server.php';

if (!isset($_GET["classId"]) || empty($_GET["classId"])) {
    die(json_encode(["success" => false, "message" => " Error: Class ID is missing!"]));
}

$classId = intval($_GET["classId"]);

$query = "SELECT noteTitle, filePath FROM notes WHERE classId = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die(json_encode(["success" => false, "message" => "Database query preparation failed!"]));
}
$stmt->bind_param("i", $classId);
$stmt->execute();
$result = $stmt->get_result();

$notes = [];
while ($row = $result->fetch_assoc()) {
    $notes[] = [
        "title" => htmlspecialchars($row['noteTitle']),
        "path" => htmlspecialchars($row['filePath'])
    ];
}

if (empty($notes)) {
    echo json_encode(["success" => false, "message" => "No notes found for this class."]);
} else {
    echo json_encode(["success" => true, "notes" => $notes]);
}
?>
