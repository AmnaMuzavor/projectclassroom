<?php
session_start();
include 'server.php';  

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

// Get the logged-in user details
$user_name = isset($_SESSION["user_name"]) ? htmlspecialchars($_SESSION["user_name"]) : "User";
$user_id = $_SESSION['user_id'];

// Created Classes
$created_query = "SELECT className, classCode FROM classes WHERE created_by = ?";
$stmt = $conn->prepare($created_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$created_classes = $stmt->get_result();

// Joined Classes
$joined_query = "SELECT c.className, c.classCode 
                 FROM class_members cm 
                 JOIN classes c ON cm.class_id = c.c_id 
                 WHERE cm.user_id = ?";
$stmt = $conn->prepare($joined_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$joined_classes = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style1.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
          <a class="navbar-brand me-auto" href="dashboard.php">Classroom</a>
          <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
            <div class="offcanvas-header">
              <h5 class="offcanvas-title">Classroom</h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
              <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                <li class="nav-item"><a class="nav-link mx-lg-2" href="dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link mx-lg-2" href="signup.php">Sign Up</a></li>
                <li class="nav-item"><a class="nav-link mx-lg-2" href="createClass.php">Create</a></li>
                <li class="nav-item"><a class="nav-link mx-lg-2" href="joinClass.php">Join</a></li>
              </ul>
            </div>
          </div>
          <a href="logout.php" class="btn btn-danger">Logout</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
    </nav>

    <!-- Welcome Section -->
    <div class="welcome">
        <div class="card text-center p-3" style="width: 82rem;">
            <div class="card-body">
                <h3 class="card-title">Welcome, <?php echo $user_name; ?>!</h3>
            </div>
        </div>
    </div>

    <!-- Created Classes Section -->
    <div class="container mt-5 pt-5">
        <h2>Your Created Classes</h2>
        <?php
        if ($created_classes->num_rows > 0) {
            while ($row = $created_classes->fetch_assoc()) {
                echo '<div class="card mb-3 ">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($row['className']) . '</h5>';
                echo '<p class="card-text">Class Code: ' . htmlspecialchars($row['classCode']) . '</p>';
                echo '<a href="notes.php?classCode=' . htmlspecialchars($row['classCode']) . '" class="btn btn-primary">Upload Notes</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No classes created yet.</p>';
        }
        ?>   
    </div>

    <!-- Joined Classes Section -->
    <div class="container mt-4">
        <h2>Joined Classes</h2>
        <?php
        if ($joined_classes->num_rows > 0) {
            while ($row = $joined_classes->fetch_assoc()) {
                echo '<div class="card mb-3">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($row["className"]) . '</h5>';
                echo '<p class="card-text">Class Code: ' . htmlspecialchars($row["classCode"]) . '</p>';

                echo '<a href="view.php?classId=CLASS_ID_HERE' . htmlspecialchars($row["classCode"]) . '" class="btn btn-success">View Notes</a>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>You haven't joined any classes yet.</p>";
        }
        ?>
    </div>



    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
