<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative; /* Add relative positioning to body */
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
        /* Positioning for the buttons */
        .action-button {
            position: absolute;
            top: 20px; /* Adjust as needed */
            z-index: 1000; /* Ensure it's above other elements */
        }
        .modify-button {
            right: 180px; /* Adjust as needed */
        }
        .logout-button {
            right: 20px; /* Adjust as needed */
        }
        .btn-action {
    width: 100%;
        }
    </style>
</head>
<body>
    <video autoplay loop muted id="video-background">
        <source src="../src/background.mp4" type="video/mp4">
    </video>
    <button type="button" id="modifyButton" class="btn btn-primary mb-3 action-button modify-button" onclick="toggleModificationForm()">
        <i class="bi bi-pencil-fill"></i> Modifier Informations
    </button>
    <a href="../logout.php" class="btn btn-danger mb-3 action-button logout-button">
        <i class="bi bi-box-arrow-right"></i> Déconnexion
    </a>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Client Dashboard</h2>
                        <?php
                        session_start(); // Start the session

                        // Check if user is not logged in, redirect to login page
                        if (!isset($_SESSION['user_id'])) {
                            header("Location: ../login.php");
                            exit();
                        }

                        // Get the user ID from the session
                        $user_id = $_SESSION['user_id'];

                        // Your database connection code
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "project";

                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Retrieve client's profile information from the database using the user ID from session
                        $sql = "SELECT u.*, c.fonction, c.nationalite, c.tel_personnel FROM utilisateur u
                                LEFT JOIN client c ON u.id = c.utilisateur_id
                                WHERE u.id = '$user_id'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            ?>
                            <!-- Display profile information -->
                            <div id="profileForm" style="display: none;">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="nom">Nom:</label>
                                        <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $row['nom']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="prenom">Prénom:</label>
                                        <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $row['prenom']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="tel">Téléphone:</label>
                                        <input type="text" class="form-control" id="tel" name="tel" value="<?php echo $row['tel']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="adresse">Adresse:</label>
                                        <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo $row['adresse']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="fonction">Fonction:</label>
                                        <input type="text" class="form-control" id="fonction" name="fonction" value="<?php echo $row['fonction']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="nationalite">Nationalité:</label>
                                        <input type="text" class="form-control" id="nationalite" name="nationalite" value="<?php echo $row['nationalite']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="tel_personnel">Téléphone Personnel:</label>
                                        <input type="text" class="form-control" id="tel_personnel" name="tel_personnel" value="<?php echo $row['tel_personnel']; ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block" name="submit">Enregistrer</button>
                                    <a href="#" onclick="cancelModification()" class="btn btn-secondary btn-block">Annuler</a>
                                </form>
                            </div>
                            <?php
                        } else {
                            echo "Aucun profil trouvé.";
                        }
                        ?>

                        <!-- Buttons to toggle modification form and disconnect -->
                        <div class="text-center">
                         <button type="button" id="serviceButton" class="btn btn-info mb-3 btn-action" onclick="redirectToRecherche()"> <i class="bi bi-list-check"></i> Consulter Service </button> </br>
                         <button type="button" id="suivreButton" class="btn btn-info mb-3 btn-action" onclick="suivreReservation()"><i class="bi bi-arrow-right"></i> Suivre la réservation réalisée</button>
                      </div>
                        <?php
                        // Handle form submission to update profile information
                        if(isset($_POST['submit'])){
                            $nom = $_POST['nom'];
                            $prenom = $_POST['prenom'];
                            $email = $_POST['email'];
                            $tel = $_POST['tel'];
                            $adresse = $_POST['adresse'];
                            $fonction = $_POST['fonction'];
                            $nationalite = $_POST['nationalite'];
                            $tel_personnel = $_POST['tel_personnel'];

                            // Update profile information in the database
                            $sql_update = "UPDATE utilisateur u
                                           LEFT JOIN client c ON u.id = c.utilisateur_id
                                           SET u.nom='$nom', u.prenom='$prenom', u.email='$email', 
                                               u.tel='$tel', u.adresse='$adresse',
                                               c.fonction='$fonction', c.nationalite='$nationalite', c.tel_personnel='$tel_personnel' 
                                           WHERE u.id='$user_id'";
                            if ($conn->query($sql_update) === TRUE) {
                                echo "<div class='alert alert-success' role='alert'>Profil mis à jour avec succès.</div>";
                            } else {
                                echo "<p class='text-danger'>Erreur lors de la mise à jour du profil: " . $conn->error . "</p>";
                                echo "<div class='alert alert-danger' role='alert'>Erreur lors de la mise à jour du profil: ". $conn->error . "</div>";
                            }
                        }

                        $conn->close();
                        ?>
                        <script>
                            function toggleModificationForm() {
                                var form = document.getElementById('profileForm');
                                var button = document.getElementById('serviceButton');
                                var button1 = document.getElementById('suivreButton');
                                var button2 = document.getElementById('reservationButton');
                                var button3 = document.getElementById('modifyButton');
                                if (form.style.display === 'none') {
                                    form.style.display = 'block';
                                    button.style.display = 'none';
                                    button1.style.display = 'none';
                                    button2.style.display = 'none';
                                    button3.style.display = 'none'; // Hide the button
                                } else {
                                    form.style.display = 'none';
                                }
                            }
                        </script>
                        <script>
                          function redirectToRecherche() {
                           window.location.href = 'consulte_service.php';
                          }
                         </script>
                        <script>
                          function suivreReservation() {
                           window.location.href = 'suivre_reservation.php';
                          }
                     </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
