<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agency Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
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
        .card {
            border-radius: 15px;
            background-color: rgba(255, 255, 255, 0.5);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.5);
            border: none;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: white;
        }
        th, td {
            border: 1px solid black;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 5px 10px;
        }
        .modif-fields {
            display: none;
        }
        .logout-button {
            right: 20px; /* Adjust as needed */
        }
        .btn-action {
         width: 100%;
        }
        .action-button {
            position: absolute;
            top: 20px; /* Adjust as needed */
            z-index: 1000; /* Ensure it's above other elements */
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
                        <h2 class="text-center mb-4">Agency Dashboard</h2>
                        <!-- Gestion de billets -->
                        <?php
                        session_start();

                        // Check if user is logged in and is an agency
                        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'agence') {
                            header("Location: ../login.php");
                            exit();
                        }
                        
                        // Logout functionality
                        if (isset($_POST['logout'])) {
                            session_unset(); // Unset all session variables
                            session_destroy(); // Destroy the session
                            header("Location: ../login.php"); // Redirect to login page
                            exit();
                        }
                        // Check if gestion de billets button is clicked
                        if(isset($_POST['gestion_billets'])) {
                            header("Location: gestion_billets.php");
                            exit();
                        }
                        elseif(isset($_POST['gestion_voyages'])) {
                            header("Location: gestion_voyages.php");
                            exit();
                        }
                        elseif(isset($_POST['gestion_restaurants'])) {
                            header("Location: gestion_restaurants.php");
                            exit();
                        }
                        ?>
                        <!-- Buttons -->
                        <form method="post">
                            <button type="submit" class="btn btn-info mb-3 btn-action" name="gestion_billets"><i class="bi bi-list-check"></i>Gestion de billets</button>
                            <button type="submit" class="btn btn-info mb-3 btn-action" name="gestion_voyages"><i class="bi bi-airplane"></i>Gestion de voyages</button>
                            <button type="submit" class="btn btn-info mb-3 btn-action" name="gestion_restaurants"><i class="bi bi-shop"></i>Gestion de restaurants</button>
                            <button type="submit" class="btn btn-danger " name="logout"><i class="bi bi-box-arrow-right"></i>Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
