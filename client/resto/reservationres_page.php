<?php
// Start the session
session_start();

// Check if the form has been submitted
if(isset($_POST['reserve'])) {
    // Retrieve restaurant ID from the form
    $restaurant_id = $_POST['restaurant_id'];
    $compte_id = $_SESSION['user_id'];

    // Store restaurant ID in session or pass it as a parameter to the reservation form
    $_SESSION['restaurant_id'] = $restaurant_id;

    // Redirect to the reservation form page
    header("Location: reservationres_form.php");
    exit;
}
?>
