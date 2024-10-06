<?php
// Start the session
session_start();

// Check if the form has been submitted
if(isset($_POST['reserve'])) {
    // Retrieve restaurant ID from the form
    $voyage_id = $_POST['voyage_id'];
    $compte_id = $_SESSION['user_id'];

    // Store restaurant ID in session or pass it as a parameter to the reservation form
    $_SESSION['voyage_id'] = $voyage_id;

    // Redirect to the reservation form page
    header("Location: reservationvoy_form.php");
    exit;
}
?>