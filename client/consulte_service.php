<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulte Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            color: #000;
            background-color: #f8f9fa;
        }
        #video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .container {
            z-index: 1;
            position: relative;
            padding-top: 80px;
        }
        h2 {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px 0;
            text-align: center;
            color: #fff;
        }
        .card-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin: 2rem 0;
        }
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            display: block;
            background-color: #fff;
            margin: 1rem;
            width: calc(33.33333% - 2rem);
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .card-text {
            color: #666;
            font-size: 1rem;
            margin-bottom: 1rem;
        }
        .card-img-top {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .btn {
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s;
            margin-top: 1rem;
            width: 100%;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .filter-list {
            list-style: none;
            padding: 0;
            display: flex;
            margin-bottom: 2rem;
        }
        .filter-list li {
            margin-right: 1rem;
        }
    </style>
</head>
<body>
    <video autoplay muted loop id="video-background">
        <source src="../src/background.mp4" type="video/mp4">
    </video>
    <h2 class="text-center mb-4">Consulte Service</h2>
    <div class="container mt-5">
        <ul class="filter-list">
            <li><button class="btn btn-primary filter-btn all active">All</button></li>
            <li><button class="btn btn-primary filter-btn restaurant">Restaurants</button></li>
            <li><button class="btn btn-primary filter-btn voyage">Voyages</button></li>
            <li><button class="btn btn-primary filter-btn billet">Billets</button></li>
        </ul>
        <div class="card-container">
            <?php
                session_start(); // Start the session
                ob_start(); // Start output buffering
                // Establishing connection to MySQL database
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "project";

                // Create connection
                $conn = mysqli_connect($servername, $username, $password, $database);

                // Check connection
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $compte_id = $_SESSION['user_id'];
                // Function to execute a query and fetch results
                function executeQuery($query) {
                    global $conn;
                    $result = mysqli_query($conn, $query);
                    if ($result) {
                        return mysqli_fetch_all($result, MYSQLI_ASSOC);
                    } else {
                        return array();
                    }
                }

                // Function to display restaurant cards
                function displayRestaurantCards($restaurants) {
                    foreach ($restaurants as $restaurant) {
                        echo '<div class="card restaurant">';
                        echo '<img src="' . $restaurant['image_path'] . '" class="card-img-top" alt="Restaurant Image">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $restaurant['nom'] . '</h5>';
                        echo '<p class="card-text">Adresse: ' . $restaurant['adresse'] . '</p>';
                        echo '<p class="card-text">N°Table: ' . $restaurant['nbrtable'] . '</p>';
                        echo '<p class="card-text">Prix: ' . $restaurant['prix'] . '$</p>';
                        echo '<p class="card-text">Agence: ' . $restaurant['nom_agence'] . '</p>';
                        echo '<p class="card-text">Contact: ' . $restaurant['nom_utilisateur'] . ' - ' . $restaurant['tel_utilisateur'] . '</p>'; // Afficher le nom d'utilisateur et son numéro de téléphone // Afficher le nom de l'agence
                        echo '<form method="post" action="resto/reservationres_page.php">';
                        echo '<input type="hidden" name="restaurant_id" value="' . $restaurant['id'] . '">';
                        echo '<button type="submit" name="reserve" class="btn btn-primary">Réserver</button>';
                        echo '</form>';
                        echo '</div></div>';
                    }
                }
                
                
                

                // Function to display voyage cards
                function displayVoyageCards($voyages) {
                    foreach ($voyages as $voyage) {
                        echo '<div class="card voyage">';
                        echo '<img src="' . $voyage['image_path'] . '" class="card-img-top" alt="Billet Image">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">Destination:' . $voyage['destination'] . '</h5>';
                        echo '<p class="card-text">Date: ' . $voyage['date_vg'] . '</p>';
                        echo '<p class="card-text">Prix: ' . $voyage['prix'] . '$</p>';
                        echo '<p class="card-text">Agence: ' . $voyage['nom_agence'] . '</p>'; // Afficher le nom de l'agence
                        echo '<p class="card-text">Contact: ' . $voyage['nom_utilisateur'] . ' - ' . $voyage['tel_utilisateur'] . '</p>'; // Afficher le nom d'utilisateur et son numéro de téléphone
                        echo '<form method="post" action="voyage/reservationvoy_page.php">';
                        echo '<input type="hidden" name="voyage_id" value="' . $voyage['id'] . '">';
                        echo '<button type="submit" name="reserve" class="btn btn-primary">Réserver</button>';
                        echo '</form>';
                        echo '</div></div>';
                    }
                }

                // Function to display billet cards
                function displayBilletCards($billets) {
                    foreach ($billets as $billet) {
                        echo '<div class="card billet">';
                        echo '<img src="' . $billet['image_path'] . '" class="card-img-top" alt="Billet Image">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">From: ' . $billet['depart'] . ' To: ' . $billet['arrivee'] . '</h5>';
                        echo '<p class="card-text">N°Ticket: ' . $billet['qte'] . '</p>';
                        echo '<p class="card-text">Prix: ' . $billet['prix'] . '$</p>';
                        echo '<p class="card-text">Disponibilité: ' . $billet['disponibilite'] . '</p>';
                        echo '<p class="card-text">Agence: ' . $billet['nom_agence'] . '</p>'; // Afficher le nom de l'agence
                        echo '<p class="card-text">Contact: ' . $billet['nom_utilisateur'] . ' - ' . $billet['tel_utilisateur'] . '</p>'; // Afficher le nom d'utilisateur et son numéro de téléphone
                        echo '<form method="post" action="billets/reservationbil_page.php">';
                        echo '<input type="hidden" name="billet_id" value="' . $billet['id_billet'] . '">';
                        echo '<button type="submit" name="reserve" class="btn btn-primary">Réserver</button>';
                        echo '</form>';
                        echo '</div></div>';
                    }
                }

                // Fetch and display all services initially
                $restaurants = executeQuery("SELECT restaurant.*, compte.username AS nom_agence, utilisateur.nom AS nom_utilisateur, utilisateur.tel AS tel_utilisateur FROM restaurant INNER JOIN compte ON restaurant.compte_id = compte.id INNER JOIN utilisateur ON compte.utilisateur_id = utilisateur.id");
                displayRestaurantCards($restaurants);

                $voyages = executeQuery("SELECT voyage.*, compte.username AS nom_agence, utilisateur.nom AS nom_utilisateur, utilisateur.tel AS tel_utilisateur FROM voyage INNER JOIN compte ON voyage.compte_id = compte.id INNER JOIN utilisateur ON compte.utilisateur_id = utilisateur.id");
                displayVoyageCards($voyages);

                $billets = executeQuery("SELECT billet.*, compte.username AS nom_agence, utilisateur.nom AS nom_utilisateur, utilisateur.tel AS tel_utilisateur FROM billet INNER JOIN compte ON billet.compte_id = compte.id INNER JOIN utilisateur ON compte.utilisateur_id = utilisateur.id");
                displayBilletCards($billets);

                // Close MySQL connection
                mysqli_close($conn);
            ?>
        </div>
        <div class="position-fixed bottom-0 end-0 p-3">
            <a href="client_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const allBtn = document.querySelector('.all');
            const restaurantBtn = document.querySelector('.restaurant');
            const voyageBtn = document.querySelector('.voyage');
            const billetBtn = document.querySelector('.billet');

            const cards = document.querySelectorAll('.card');

            allBtn.addEventListener('click', function () {
                cards.forEach(card => {
                    card.style.display = 'block';
                });
                this.classList.add('active');
                restaurantBtn.classList.remove('active');
                voyageBtn.classList.remove('active');
                billetBtn.classList.remove('active');
            });

            restaurantBtn.addEventListener('click', function () {
                cards.forEach(card => {
                    card.style.display = card.classList.contains('restaurant') ? 'block' : 'none';
                });
                this.classList.add('active');
                allBtn.classList.remove('active');
                voyageBtn.classList.remove('active');
                billetBtn.classList.remove('active');
            });

            voyageBtn.addEventListener('click', function () {
                cards.forEach(card => {
                    card.style.display = card.classList.contains('voyage') ? 'block' : 'none';
                });
                this.classList.add('active');
                allBtn.classList.remove('active');
                restaurantBtn.classList.remove('active');
                billetBtn.classList.remove('active');
            });

            billetBtn.addEventListener('click', function () {
                cards.forEach(card => {
                    card.style.display = card.classList.contains('billet') ? 'block' : 'none';
                });
                this.classList.add('active');
                allBtn.classList.remove('active');
                restaurantBtn.classList.remove('active');
                voyageBtn.classList.remove('active');
            });
        });
    </script>
</body>
</html>