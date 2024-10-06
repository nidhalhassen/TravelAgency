<?php
ob_start(); // Start output buffering
session_start(); // Start the session

// Check if user is logged in and is authorized
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'agence') {
    header("Location: ../login.php");
    exit();
}

// Initialize variables
$modifier_nom = $modifier_adresse = "";
$error_message = $success_message = "";

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }
    $imagePath = '';
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter_restaurant'])) {
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
    // Add a new restaurant
    if (isset($_POST['ajouter_restaurant'])) {
        $nom = $_POST['nom'];
        $adresse = $_POST['adresse'];
        $nbrtable = intval($_POST['nbrtable']);
        $prix = $_POST['prix'];
        $disponibilite = ($nbrtable > 0) ? 'available' : 'not available';

        $sql = "INSERT INTO restaurant (nom, adresse, disponibilite, nbrtable, compte_id, image_path, prix) VALUES ('$nom', '$adresse', '$disponibilite', '$nbrtable','$compte_id','$imagePath','$prix')";
        if ($conn->query($sql) === TRUE) {
            $success_message = "Restaurant ajouté avec succès";
        } else {
            $error_message = "Erreur : " . $sql . "<br>" . $conn->error;
        }
    }

    // Modify a restaurant
    if (isset($_POST['modifier_restaurant'])) {
        $id_restaurant = $_POST['id_restaurant'];
        // Fetch restaurant details
        $sql = "SELECT * FROM restaurant WHERE id=$id_restaurant";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Set values for modification
            $modifier_nom = $row['nom'];
            $modifier_adresse = $row['adresse'];
            $modifier_nbrtable = $row['nbrtable'];
            $modifier_prix = $row['prix'];
        }
    }

    // Update a restaurant
    if (isset($_POST['update_restaurant'])) {
        $id_restaurant = $_POST['id_restaurant'];
        $nom = $_POST['modifier_nom'];
        $adresse = $_POST['modifier_adresse'];
        $nbrtable = $_POST['modifier_nbrtable'];
        $prix = $_POST['prix'];

        $sql = "UPDATE restaurant SET nom='$nom', adresse='$adresse',nbrtable='$nbrtable', prix='$prix' WHERE id=$id_restaurant";
        if ($conn->query($sql) === TRUE) {
            $success_message = "Restaurant modifié avec succès";
        } else {
            $error_message = "Erreur : " . $sql . "<br>" . $conn->error;
        }
    }

    // Delete a restaurant
    if (isset($_POST['supprimer_restaurant'])) {
        $id_restaurant = $_POST['id_restaurant'];
        $sql = "DELETE FROM restaurant WHERE id=$id_restaurant";
        if ($conn->query($sql) === TRUE) {
            $success_message = "Restaurant supprimé avec succès";
        } else {
            $error_message = "Erreur : " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des restaurants</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Gestion des restaurants</h2>
                        <form method="post" action="" enctype="multipart/form-data">
                                <div class="form-group">
                                  <label for="image">Image:</label>
                                  <input type="file" id="image" name="image" class="form-control">
                                </div>
                            <div class="form-group">
                                <label for="nom">Nom du restaurant:</label>
                                <input type="text" id="nom" name="nom" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="adresse">Adresse du restaurant:</label>
                                <input type="text" id="adresse" name="adresse" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="nbrtable">Nombre De table:</label>
                                <input type="text" id="nbrtable" name="nbrtable" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="nbrtable">Prix:</label>
                                <input type="text" id="prix" name="prix" class="form-control" required>
                            </div>
                            <input type="submit" name="ajouter_restaurant" value="Ajouter" class="btn btn-primary">
                        </form>
                        <div class="table-responsive mt-4">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom du restaurant</th>
                                        <th>Adresse</th>
                                        <th>Disponibilite</th>
                                        <th>Nombre Table</th>
                                        <th>Prix</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Database connection
                                    $conn = new mysqli($servername, $username, $password, $dbname);
                                    if ($conn->connect_error) {
                                        die("La connexion a échoué : " . $conn->connect_error);
                                    }

                                    $compte_id = $_SESSION['user_id'];
                                    // Display restaurants
                                    $sql = "SELECT * FROM restaurant WHERE compte_id = $compte_id";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row["id"] . "</td>";
                                            echo "<td>" . $row["nom"] . "</td>";
                                            echo "<td>" . $row["adresse"] . "</td>";
                                            echo "<td>" . $row["disponibilite"] . "</td>";
                                            echo "<td>" . $row["nbrtable"] . "</td>";
                                            echo "<td>" . $row["prix"] . "</td>";
                                            echo "<td>
                                                <form method='post' action=''>
                                                    <input type='hidden' name='id_restaurant' value='" . $row["id"] . "'>
                                                    <input type='submit' name='modifier_restaurant' value='Modifier' class='btn btn-primary'>
                                                    <input type='submit' name='supprimer_restaurant' value='Supprimer' onclick='return confirmDelete();' class='btn btn-danger'>
                                                </form>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>Aucun restaurant trouvé</td></tr>";
                                    }
                                    echo "<script>
                                    function confirmDelete() {
                                       return confirm('Êtes-vous sûr de vouloir supprimer ce billet ?');
                                    }
                                    </script>";
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if(isset($_POST['modifier_restaurant'])): ?>
                            <hr>
                            <h3>Modifier Restaurant</h3>
                            <form method="post" action="">
                                <input type="hidden" name="id_restaurant" value="<?php echo $_POST['id_restaurant']; ?>">
                                <div class="form-group">
                                    <label for="modifier_nom">Nom du restaurant:</label>
                                    <input type="text" id="modifier_nom" name="modifier_nom" class="form-control" value="<?php echo $modifier_nom; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="modifier_adresse">Adresse du restaurant:</label>
                                    <input type="text" id="modifier_adresse" name="modifier_adresse" class="form-control" value="<?php echo $modifier_adresse; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="modifier_nbrtable">Nom du restaurant:</label>
                                    <input type="text" id="modifier_nbrtable" name="modifier_nbrtable" class="form-control" value="<?php echo $modifier_nbrtable; ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="modifier_nbrtable">Prix:</label>
                                    <input type="text" id="prix" name="prix" class="form-control" value="<?php echo $modifier_nbrtable; ?>" required>
                                </div>
                                <input type="submit" name="update_restaurant" value="Modifier" class="btn btn-primary">
                            </form>
                        <?php endif; ?>
                        <?php if(!empty($error_message)): ?>
    <div class="alert alert-danger mt-3" role="alert">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>
<?php if(!empty($success_message)): ?>
    <div class="alert alert-success mt-3" role="alert">
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>
                        <a href="agency_dashboard.php" class="btn btn-secondary mb-3">Retour au tableau de bord</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
