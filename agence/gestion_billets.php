<?php
ob_start(); // Start output buffering
session_start(); // Start the session
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des billets d'avion</title>
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Créer un nouveau billet</h2>
                        <form method="post" action="" enctype="multipart/form-data">
                        <div class="form-group">
                                  <label for="image">Image:</label>
                                  <input type="file" id="image" name="image" class="form-control">
                                </div>
                            <div class="form-group">
                                <label for="numero_de_voyage">Numéro de voyage:</label>
                                <input type="text" id="numero_de_voyage" name="numero_de_voyage" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="depart">Départ:</label>
                                <input type="text" id="depart" name="depart" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="arrivee">Arrivée:</label>
                                <input type="text" id="arrivee" name="arrivee" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="type">Type:</label>
                                <select id="type" name="type" class="form-control" required>
                                    <option value="economy">Economy</option>
                                    <option value="business class">Business Class</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="qte">Quantité:</label>
                                <input type="number" id="qte" name="qte" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="qte">Prix:</label>
                                <input type="text" id="prix" name="prix" class="form-control">
                            </div>
                            <input type="submit" name="ajouter_billet" value="Ajouter" class="btn btn-primary">
                        </form>
                        <div class="table-responsive mt-4">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Numéro de voyage</th>
                                        <th>Départ</th>
                                        <th>Arrivée</th>
                                        <th>Type</th>
                                        <th>Disponibilite</th>
                                        <th>Quantite</th>
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
                                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter_billet'])) {
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

                                    // Créer un billet
                                    if (isset($_POST['ajouter_billet'])) {
                                        $numero_de_voyage = $_POST['numero_de_voyage'];
                                        $depart = $_POST['depart'];
                                        $arrivee = $_POST['arrivee'];
                                        $type = $_POST['type'];
                                        $qte = intval($_POST['qte']);
                                        $prix = $_POST['prix'];
                                        $disponibilite = ($qte > 0) ? 'available' : 'not available';
                                        $sql = "INSERT INTO billet (numero_de_voyage, depart, arrivee, type, disponibilite, qte, compte_id, image_path, prix) VALUES ('$numero_de_voyage', '$depart', '$arrivee', '$type', '$disponibilite', '$qte', $compte_id, '$imagePath','$prix')";
                                        if ($conn->query($sql) === TRUE) {
                                            echo "Billet ajouté avec succès";
                                        } else {
                                            echo "Erreur : " . $sql . "<br>" . $conn->error;
                                        }
                                    }

                                    // Modifier un billet
                                    if (isset($_POST['modifier_billet'])) {
                                        $id_billet = $_POST['id_billet'];
                                        // Afficher le formulaire de modification avec les données actuelles
                                        $sql = "SELECT * FROM billet WHERE id_billet = $id_billet";
                                        $result = $conn->query($sql);
                                        $row = $result->fetch_assoc();
                                        ?>
                                        <!-- Formulaire de modification -->
                                        <form method="post" action="">
                                            <input type="hidden" name="id_billet" value="<?php echo $row['id_billet']; ?>">
                                            Numéro de voyage: <input type="text" name="numero_de_voyage" class="form-control" value="<?php echo $row['numero_de_voyage']; ?>"><br>
                                            Départ: <input type="text" name="depart" class="form-control"value="<?php echo $row['depart']; ?>"><br>
                                            Arrivée: <input type="text" name="arrivee" class="form-control" value="<?php echo $row['arrivee']; ?>"><br>
                                            Type: 
                                            <select id="type" name="type" class="form-control" required>
                                                <option value="economy"<?php if($row['type'] == 'economy') echo ' selected'; ?>>Economy</option>
                                                <option value="business class"<?php if($row['type'] == 'business class') echo ' selected'; ?>>Business Class</option>
                                            </select><br>
                                            Quantite: <input type="number" name="qte" class="form-control" value="<?php echo $row['qte']; ?>"><br>
                                            Prix: <input type="number" name="prix" class="form-control" value="<?php echo $row['prix']; ?>"><br>
                                            <input type="submit" name="update_billet" class="btn btn-primary" value="Mettre à jour">
                                        </form>
                                        <?php
                                    }

                                    // Mettre à jour un billet
                                    if (isset($_POST['update_billet'])) {
                                        $id_billet = $_POST['id_billet'];
                                        $numero_de_voyage = $_POST['numero_de_voyage'];
                                        $depart = $_POST['depart'];
                                        $arrivee = $_POST['arrivee'];
                                        $type = $_POST['type'];
                                        $qte = $_POST['qte'];
                                        $prix = $_POST['prix'];

                                        $sql = "UPDATE billet SET numero_de_voyage='$numero_de_voyage', depart='$depart', arrivee='$arrivee', type='$type', qte='$qte', prix='$prix' WHERE id_billet=$id_billet";
                                        if ($conn->query($sql) === TRUE) {
                                            echo "Billet mis à jour avec succès";
                                        } else {
                                            echo "Erreur : " . $sql . "<br>" . $conn->error;
                                        }
                                    }

                                    // Supprimer un billet
                                    if (isset($_POST['supprimer_billet'])) {
                                        $id_billet = $_POST['id_billet'];
                                        $sql = "DELETE FROM billet WHERE id_billet=$id_billet";
                                        if ($conn->query($sql) === TRUE) {
                                            echo "Billet supprimé avec succès";
                                        } else {
                                            echo "Erreur : " . $sql . "<br>" . $conn->error;
                                        }
                                    }

                                    // Afficher la liste des billets
                                    $sql = "SELECT * FROM billet WHERE compte_id = $compte_id";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row["id_billet"] . "</td>";
                                            echo "<td>" . $row["numero_de_voyage"] . "</td>";
                                            echo "<td>" . $row["depart"] . "</td>";
                                            echo "<td>" . $row["arrivee"] . "</td>";
                                            echo "<td>" . $row["type"] . "</td>";
                                            echo "<td>" . $row["disponibilite"] . "</td>";
                                            echo "<td>" . $row["qte"] . "</td>";
                                            echo "<td>" . $row["prix"] . "</td>";
                                            echo "<td>
                                                <form method='post' action=''>
                                                    <input type='hidden' name='id_billet' value='" . $row["id_billet"] . "'>
                                                    <input type='submit' name='modifier_billet' value='Modifier' class='btn btn-primary'>
                                                    <input type='submit' name='supprimer_billet' value='Supprimer' onclick='return confirmDelete();' class='btn btn-danger'>
                                                </form>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>Aucun résultat trouvé</td></tr>";
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
                            <a href="agency_dashboard.php" class="btn btn-secondary mb-3">Retour au tableau de bord</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript to change the select field's appearance -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const typeSelect = document.getElementById('type');
            typeSelect.classList.add('form-select');
        });
    </script>
</body>
</html>
