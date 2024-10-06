<?php
// Start the session
session_start();

// Check if the form has been submitted
if(isset($_POST['reserve'])) {
    // Retrieve restaurant ID from the form
    $billet_id = $_POST['billet_id'];
    $compte_id = $_SESSION['user_id'];

    // Store restaurant ID in session or pass it as a parameter to the reservation form
    $_SESSION['billet_id'] = $billet_id;

    // Redirect to the reservation form page
    header("Location: reservationbil_form.php");
    exit;
}
?>
