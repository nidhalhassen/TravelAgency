<?php
session_start();
if(isset($_SESSION['user_id'])) {
    // Retrieve the account ID from the session
    $account_id = $_SESSION['user_id'];
    

    // Connect to the database
    // Replace the database credentials with your own
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if(isset($_POST['cancel_reservation'])) {
        $reservation_id = $_POST['cancel_reservation'];
        // Prepare and execute the SQL query to delete the reservation
        $sql = "DELETE FROM reservation WHERE id = s";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $reservation_id);
        
        // Execute the statement and check for errors
        if ($stmt->execute()) {
            // Redirect back to the page where reservations are listed
            header("Location: suivre_reservation.php");
            exit();
        } else {
            // Handle any errors that occurred during the deletion process
            echo "Error: " . $conn->error;
        }
    }
}
?>
