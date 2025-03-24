<?php
session_start();
include 'server.php'; 

if (!isset($_GET['classCode'])) {
    die(" Error: Class Code is missing from the URL!");
}

$classCode = $_GET['classCode'];


$stmt = $conn->prepare("SELECT c_id FROM classes WHERE classCode = ?");
$stmt->bind_param("s", $classCode);
$stmt->execute();
$result = $stmt->get_result();
$classRow = $result->fetch_assoc();
$classId = $classRow ? $classRow['c_id'] : null;

if (!$classId) {
    die(" Error: No class found for this Class Code!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <title>Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="main.js"></script>

<style>
        body {
            background-color: #f8f9fa; 
        }
        .form {
            background-color: #e4caa4; 
            padding: 20px;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .color-btn {
            background-color: #455763; 
            color: white; 
            border: none;
        }
        .color-btn:hover {
            background-color:rgb(181, 214, 236); 
            color: #455763; 
        
        }
        .note {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: white;
            margin-bottom: 10px;
        }
       
    </style>
            
     
</head>
<body>
    <input type="hidden" id="classId" value="<?php echo htmlspecialchars($classId); ?>">
    <form id="uploadNoteForm" enctype="multipart/form-data" class="form">
        <h3 class="mb-3 text-center">Upload Note</h3>

        <div class="mb-3">
            <label for="noteTitle" class="form-label">Note Title:</label>
            <input type="text" id="noteTitle" name="noteTitle" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="noteFile" class="form-label">Upload File:</label>
            <input type="file" id="noteFile" name="noteFile" class="form-control" required>
        </div>

        <button type="submit" class="btn color-btn ">Upload Note</button>
    </form>
<!-- 
    <form id="uploadNoteForm" enctype="multipart/form-data">
        <label for="noteTitle">Note Title:</label>
        <input type="text" id="noteTitle" name="noteTitle" required><br><br>

        <label for="noteFile">Upload File:</label>
        <input type="file" id="noteFile" name="noteFile" required><br><br>

        <button type="submit">Upload Note</button>
    </form>
    -->

    <div id="message"></div>
    <div id="notesList"></div>


   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



