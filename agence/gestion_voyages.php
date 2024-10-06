<?php
ob_start(); // Start output buffering
session_start(); // Start the session

// Define variables for success and error messages
$success_message = "";
$error_message = "";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des voyages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
                body {
            background-image: url('src/gere.png'); /* Replace 'gere.png' with the path to your background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .container {
            padding: 50px 0;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.5); /* Adjust transparency by changing the alpha value */
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.7); /* Adjust transparency by changing the alpha value */
            border: none;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: transparency;
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
        .success-message {
            color: #28a745;
            font-weight: bold;
        }
        .error-message {
            color: #dc3545;
            font-weight: bold;
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
    </style>
</head>
<body>
<video autoplay loop muted id="video-background">
        <source src="../src/background.mp4" type="video/mp4">
    </video>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Gestion des voyages</h2>
                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="image">Image:</label>
                                <input type="file" id="image" name="image" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="date_vg">Date du voyage:</label>
                                <input type="date" id="date_vg" name="date_vg" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="destination">Destination:</label>
                                <input type="text" id="destination" name="destination" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="programme">Programme:</label>
                                <textarea id="programme" name="programme" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="nbrpass">Nombre Passages:</label>
                                <input type="number" id="nbrpass" name="nbrpass" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="nbrpass">Prix:</label>
                                <input type="text" id="prix" name="prix" class="form-control" required>
                            </div>
                            <input type="submit" name="ajouter_voyage" value="Ajouter" class="btn btn-primary">
                        </form>
                        <div class="table-responsive mt-4">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date du voyage</th>
                                        <th>Destination</th>
                                        <th>Programme</th>
                                        <th>Disponibilite</th>
                                        <th>No Passages</th>
                                        <th>Prix</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Check if user is logged in and is an agency
                                    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'agence') {
                                        header("Location: ../login.php");
                                        exit();
                                    }
                                    $imagePath = '';
                                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter_voyage'])) {
                                        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                            $uploadDir = '../uploads/'; // Directory to store uploaded images
                                            $uploadFile = $uploadDir . basename($_FILES['image']['name']);
                                    
                                            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                                                $imagePath = $uploadFile;
                                            } else {
                                                echo "Failed to upload image.";
                                            }
                                        } else {
                                            echo "Error uploading image.";
                                        }
                                    }                                    
                                    $compte_id = $_SESSION['user_id'];
                                    // Connexion à la base de données
                                    $servername = "localhost";
                                    $username = "root";
                                    $password = "";
                                    $dbname = "project";

                                    $conn = new mysqli($servername, $username, $password, $dbname);
                                    if ($conn->connect_error) {
                                        die("La connexion a échoué : " . $conn->connect_error);
                                    }

                                    // Créer un voyage
                                    if (isset($_POST['ajouter_voyage'])) {
                                        $date_vg = $_POST['date_vg'];
                                        $destination = $_POST['destination'];
                                        $programme = $_POST['programme'];
                                        $prix = $_POST['prix'];
                                        $nbrpass = intval($_POST['nbrpass']);
                                        $disponibilite = ($nbrpass > 0) ? 'available' : 'not available';


                                        $sql = "INSERT INTO voyage (date_vg, destination, programme, disponibilite, nbrpass, compte_id, image_path, prix) VALUES ('$date_vg', '$destination', '$programme', '$disponibilite','$nbrpass','$compte_id','$imagePath','$prix')";
                                        if ($conn->query($sql) === TRUE) {
                                            $success_message = '<div class="alert alert-success" role="alert">Voyage ajouté avec succès</div>';
                                        } else {
                                            $error_message = "Erreur : " . $sql . "<br>" . $conn->error;
                                        }
                                    }

                                    // Modifier un voyage
                                    if (isset($_POST['modifier_voyage'])) {
                                        $id_voyage = $_POST['id_voyage'];
                                        // Afficher le formulaire de modification avec les données actuelles
                                        $sql = "SELECT * FROM voyage WHERE id = $id_voyage";
                                        $result = $conn->query($sql);
                                        $row = $result->fetch_assoc();
                                        ?>
                                        <!-- Formulaire de modification -->
                                        <form method="post" action="">
                                            <input type="hidden" name="id_voyage" value="<?php echo $row['id']; ?>">
                                            <div class="form-group">
                                                <label for="date_vg">Date du voyage:</label>
                                                <input type="date" id="date_vg" name="date_vg" class="form-control" value="<?php echo $row['date_vg']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="destination">Destination:</label>
                                                <input type="text" id="destination" name="destination" class="form-control" value="<?php echo $row['destination']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="programme">Programme:</label>
                                                <textarea id="programme" name="programme" class="form-control" rows="4"><?php echo $row['programme']; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="programme">Prix:</label>
                                                <textarea id="prix" name="prix" class="form-control" rows="4"><?php echo $row['prix']; ?></textarea>
                                            </div>
                                            <input type="submit" name="update_voyage" value="Mettre à jour" class="btn btn-primary">
                                        </form>
                                        <?php
                                    }

                                    // Mettre à jour un voyage
                                    if (isset($_POST['update_voyage'])) {
                                        $id_voyage = $_POST['id_voyage'];
                                        $date_vg = $_POST['date_vg'];
                                        $destination = $_POST['destination'];
                                        $programme = $_POST['programme'];
                                        $prix = $_POST['prix'];

                                        $sql = "UPDATE voyage SET date_vg='$date_vg', destination='$destination', programme='$programme', prix='$prix' WHERE id=$id_voyage";
                                        if ($conn->query($sql) === TRUE) {
                                            $success_message = "Voyage mis à jour avec succès";
                                        } else {
                                            $error_message = "Erreur : " . $sql . "<br>" . $conn->error;
                                        }
                                    }

                                    // Supprimer un voyage
                                    if (isset($_POST['supprimer_voyage'])) {
                                        $id_voyage = $_POST['id_voyage'];
                                        $sql = "DELETE FROM voyage WHERE id=$id_voyage";
                                        if ($conn->query($sql) === TRUE) {
                                            $success_message = "Voyage supprimé avec succès";
                                        } else {
                                            $error_message = "Erreur : " . $sql . "<br>" . $conn->error;
                                        }
                                    }

                                    // Afficher la liste des voyages
                                    $sql = "SELECT * FROM voyage WHERE compte_id = $compte_id";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row["id"] . "</td>";
                                            echo "<td>" . $row["date_vg"] . "</td>";
                                            echo "<td>" . $row["destination"] . "</td>";
                                            echo "<td>" . $row["programme"] . "</td>";
                                            echo "<td>" . $row["disponibilite"] . "</td>";
                                            echo "<td>" . $row["nbrpass"] . "</td>";
                                            echo "<td>" . $row["prix"] . "</td>";
                                            echo "<td>
                                                <form method='post' action=''>
                                                    <input type='hidden' name='id_voyage' value='" . $row["id"] . "'>
                                                    <input type='submit' name='modifier_voyage' value='Modifier' class='btn btn-primary'>
                                                    <input type='submit' name='supprimer_voyage' value='Supprimer' onclick='return confirmDelete();' class='btn btn-danger'>
                                                </form>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>Aucun résultat trouvé</td></tr>";
                                    }
                                    echo "<script>
                                    function confirmDelete() {
                                       return confirm('Êtes-vous sûr de vouloir supprimer ce billet ?');
                                    }
                                    </script>";
                                    $conn->close();
                                    ob_end_flush(); // Flush the output buffer and send output to the browser
                                    ?>
                                </tbody>
                            </table>
                            <!-- Display success or error message -->
                            <?php if (!empty($success_message)) { ?>
                                <p class="success-message"><?php echo $success_message; ?></p>
                            <?php } ?>
                            <?php if (!empty($error_message)) { ?>
                                <p class="error-message"><?php echo $error_message; ?></p>
                            <?php } ?>
                            <a href="agency_dashboard.php" class="btn btn-secondary mb-3">Retour au tableau de bord</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
