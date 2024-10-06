<?php
session_start(); // Start the session

// Include database connection file
include 'db_connection.php';

// Check if user is already logged in, redirect to appropriate dashboard
if(isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] == 'client') {
        header("Location: client/client_dashboard.php");
    } elseif ($_SESSION['user_type'] == 'agence') {
        header("Location: agence/agency_dashboard.php");
    }
    exit();
}

$error_message = ""; 

// Handle form submission
if(isset($_POST['submit'])){
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project";
    // Establish connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Get inputs
    $user = $_POST["username"];
    $pass = $_POST["password"];
    // Search for inputs in db
    $sql = "SELECT * FROM compte WHERE username='$user' AND password='$pass'";
    // Obtain result
    $result = $conn->query($sql);
    // Login process
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Fetch the first row
        if ($row["status"] == "activated") { // Check if account is activated
            $_SESSION['user_id'] = $row['utilisateur_id']; // Set user ID in session
            $_SESSION['user_type'] = $row['type']; // Set user type in session
            if ($row["type"] == "client") {
                // Redirect to client dashboard
                header("Location: client/client_dashboard.php");
            } elseif ($row["type"] == "agence") {
                // Redirect to agency dashboard
                header("Location: agence/agency_dashboard.php");
            }
            exit();
        } else {
            // Redirect to unauthorized page
            header("Location: unauthorized.php");
            exit();
        }
    } else {
        $error_message = "❌ Invalid username or password.";
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 15px;
            background-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.5);
            border: none;
            border-radius: 15px; /* Rounded corners */
            padding: 15px; /* Padding */
            box-shadow: none; /* Remove shadow */
        }
        .welcome-message {
            margin-top: 20px;
            text-align: center;
        }
        #video-background {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1000;
            overflow: hidden;
        }
        .create-account-link {
            color: black;
        }
    </style>
</head>
<body>
    <video autoplay loop muted id="video-background">
        <source src="src/background.mp4" type="video/mp4">
    </video>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Login</h2>
                        <form method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" name="submit">Login</button>
                            <a href="Create_account.php" class="btn btn-outline-secondary">Don't have an account?</a>
                        </form>
                        <?php
                        // Display error message if exists
                        if(!empty($error_message)) {
                            echo "<div class='welcome-message'>$error_message</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
