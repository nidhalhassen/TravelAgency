<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "project");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if service and location/name are provided
if (isset($_GET['service'])) {
    $service = $_GET['service'];

    if ($service === "billet") {
        if (isset($_GET['location'])) {
            $location = $_GET['location'];
            // Search for flight tickets in the database based on the provided location
            $sql = "SELECT * FROM billet WHERE depart = '$location' OR arrivee = '$location'";
            $result = mysqli_query($conn, $sql);
            // Display search results
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<p>{$row['numero_de_voyage']} - Départ: {$row['depart']} - Arrivée: {$row['arrivee']} - Type: {$row['type']}</p>";
                }
            } else {
                echo "Aucun billet d'avion trouvé pour cette destination.";
            }
        }
    } elseif ($service === "voyage") {
        if (isset($_GET['location'])) {
            $location = $_GET['location'];
            // Search for voyage offers in the database based on the provided location
            $sql = "SELECT * FROM voyage WHERE destination = '$location'";
            $result = mysqli_query($conn, $sql);
            // Display search results
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<p>Destination: {$row['destination']} - Date: {$row['date_vg']} - Programme: {$row['programme']}</p>";
                }
            } else {
                echo "Aucune offre de voyage trouvée pour cette destination.";
            }
        }
    } elseif ($service === "restaurant") {
        if (isset($_GET['name'])) {
            $name = $_GET['name'];
            // Search for restaurants in the database based on the provided name
            $sql = "SELECT * FROM restaurant WHERE nom = '$name'";
            $result = mysqli_query($conn, $sql);
            // Display search results
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<p>{$row['nom']} - Location: {$row['adresse']}</p>";
                }
            } else {
                echo "Aucun restaurant trouvé avec ce nom.";
            }
        }
    }
}

// Close database connection
mysqli_close($conn);
?>
