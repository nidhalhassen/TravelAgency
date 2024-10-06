<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the account ID from the POST request
$id = $_POST['id'];

// SQL query to update the status of the account to activated
$sql = "UPDATE compte SET status = 'activated' WHERE utilisateur_id = $id";

if ($conn->query($sql) === TRUE) {
    // Redirect back to the account list page
    header("Location: account_list.php");
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
