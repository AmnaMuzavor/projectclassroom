<?php
include 'server.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["classId"]) || empty($_POST["classId"])) {
        echo json_encode(["success" => false, "message" => "Class ID is missing!"]);
        exit();
    }

    $noteTitle = trim($_POST["noteTitle"]);
    $classId = intval($_POST["classId"]); 
    $uploadedBy = $_SESSION["user_id"];

    // Check if class ID exists in the database
    $classCheck = $conn->prepare("SELECT c_id FROM classes WHERE c_id = ?");
    $classCheck->bind_param("i", $classId);
    $classCheck->execute();
    $classCheck->store_result();

    if ($classCheck->num_rows == 0) {
        echo json_encode(["success" => false, "message" => " Class ID does not exist!"]);
        exit();
    }

    //  Ensure a file is uploaded
    if (!isset($_FILES["noteFile"]) || $_FILES["noteFile"]["error"] != UPLOAD_ERR_OK) {
        echo json_encode(["success" => false, "message" => " No file uploaded or upload error!"]);
        exit();
    }

    //  Ensure the uploads directory exists
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate a unique filename to prevent overwrites
    $fileExt = pathinfo($_FILES["noteFile"]["name"], PATHINFO_EXTENSION);
    $uniqueFileName = uniqid("note_", true) . "." . $fileExt;
    $filePath = $uploadDir . $uniqueFileName;

    // Move the file to the uploads directory
    if (!move_uploaded_file($_FILES["noteFile"]["tmp_name"], $filePath)) {
        echo json_encode(["success" => false, "message" => "File upload failed!"]);
        exit();
    }

    //  Insert note details into database
    $stmt = $conn->prepare("INSERT INTO notes (noteTitle, filePath, uploadedBy, classId) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $noteTitle, $filePath, $uploadedBy, $classId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => " Note uploaded successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => " Database insert error!"]);
    }
}
?>
