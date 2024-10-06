<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.5);
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
        .form-control {
            background-color: rgba(255, 255, 255, 0.5); /* Transparent white background */
            border: none; /* Remove border if needed */
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
                        <h2 class="text-center mb-4">Create Account</h2>
                        <form method="post" action="Create_account.php">
                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                            <div class="form-group">
                                <label for="prenom">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="tel">Tel</label>
                                <input type="tel" class="form-control" id="tel" name="tel" required>
                            </div>
                            <div class="form-group">
                                <label for="addresse">Addresse</label>
                                <input type="text" class="form-control" id="addresse" name="adresse" required>
                            </div>
                            <div class="form-group">
                                <label for="type">Type of Account</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="">Select Account Type</option>
                                    <option value="client">Client</option>
                                    <option value="agence">Agence</option>
                                </select>
                            </div>
                            <!-- Additional fields for agency type -->
                            <div class="form-group" id="agence_fields" style="display: none;">
                                <label for="nom_agence">Nom Agence</label>
                                <input type="text" class="form-control" id="nom_agence" name="nom_agence">
                            </div>
                            <!-- Additional fields for client type -->
                            <div class="form-group" id="client_fields" style="display: none;">
                                <label for="nationalite">Nationalité</label>
                                <input type="text" class="form-control" id="nationalite" name="nationalite">
                                <label for="fonction">Fonction</label>
                                <input type="text" class="form-control" id="fonction" name="fonction">
                                <label for="tel_personnel">Tel Personnel</label>
                                <input type="tel" class="form-control" id="tel_personnel" name="tel_personnel">
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary btn-block">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('type').addEventListener('change', function () {
            var type = this.value;
            var agenceFields = document.getElementById('agence_fields');
            var clientFields = document.getElementById('client_fields');

            if (type === 'agence') {
                agenceFields.style.display = 'block';
                clientFields.style.display = 'none';
            } else if (type === 'client') {
                clientFields.style.display = 'block';
                agenceFields.style.display = 'none';
            } else {
                agenceFields.style.display = 'none';
                clientFields.style.display = 'none';
            }
        });
    </script>
    <?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $username1 = $_POST["username"];
    $password1 = $_POST["password"];
    $email = $_POST["email"];
    $tel = $_POST["tel"];
    $adresse = $_POST["adresse"];
    $type = $_POST["type"];
    $status = "not activated";
    
    // Additional fields for agence type
    $nom_agence = ($type == 'agence') ? $_POST["nom_agence"] : NULL;

    // Additional fields for client type
    $nationalite = ($type == 'client') ? $_POST["nationalite"] : NULL;
    $fonction = ($type == 'client') ? $_POST["fonction"] : NULL;
    $tel_personnel = ($type == 'client') ? $_POST["tel_personnel"] : NULL;

    // Establish database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "project";
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into utilisateur table
    $sql_user = "INSERT INTO utilisateur (nom, prenom, email, tel, adresse) VALUES ('$nom', '$prenom', '$email','$tel','$adresse')";
    if ($conn->query($sql_user) === FALSE) {
        echo "Error: " . $sql_user . "<br>" . $conn->error;
    }

    // Get the utilisateur ID
    $utilisateur_id = $conn->insert_id;

    // Insert data into compte table
    $sql_compte = "INSERT INTO compte (username, password, utilisateur_id,status,type) VALUES ('$username1', '$password1', '$utilisateur_id','$status','$type')";
    if ($conn->query($sql_compte) === FALSE) {
        echo "Error: " . $sql_compte . "<br>" . $conn->error;
    }

    // Insert data based on account type
    if ($type == 'agence') {
        $sql_agence = "INSERT INTO agence (nom_agence, utilisateur_id) VALUES ('$nom_agence', '$utilisateur_id')";
        if ($conn->query($sql_agence) === FALSE) {
            echo "Error: " . $sql_agence . "<br>" . $conn->error;
        }
    } elseif ($type == 'client') {
        $sql_client = "INSERT INTO client (nationalite, fonction, tel_personnel, utilisateur_id) VALUES ('$nationalite', '$fonction', '$tel_personnel', '$utilisateur_id')";
        if ($conn->query($sql_client) === FALSE) {
            echo "Error: " . $sql_client . "<br>" . $conn->error;
        }
    }
    echo '<script>
            setTimeout(function() {
                window.location.href = "login.php";
            }, 2000);
          </script>';
    $conn->close();
}
?>
</body>
</html>