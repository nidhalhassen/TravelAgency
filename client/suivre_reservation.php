<!DOCTYPE html>
<html>
<head>
    <title>View Reservations</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .star-rating {
            font-size: 1.5em;
            color: #ffd700; /* Gold color for stars */
        }
    </style>
</head>
<body>

<div class="container">
    <h2>View Reservations</h2>

    <?php
    // Start the session
    session_start();

    // Check if user_id is set in the session
    if(isset($_SESSION['user_id'])) {
        // Retrieve the account ID from the session
        $account_id = $_SESSION['user_id'];

        // Connect to the database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "project";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if payer button is clicked
        if(isset($_POST['payer'])) {
            $reservation_id = $_POST['reservation_id'];
            // Redirect to payment page with reservation details
            header("Location: paiement.php");
            exit();
        }

        // Check if cancel button is clicked
        if(isset($_POST['cancel_reservation'])) {
            $reservation_id = $_POST['cancel_reservation'];
            // Prepare and execute the SQL query to delete the reservation
            $sql = "DELETE FROM reservation WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $reservation_id);
            $stmt->execute();
            // Redirect back to the page where reservations are listed
            header("Location: suivre_reservation.php");
            exit();
        }

        // Prepare SQL query to retrieve reservations made by the account along with user names and reputation
        $sql = "SELECT reservation.id, 
                       reservation.type, 
                       reservation.name AS reservation_name,
                       CASE
                           WHEN reservation.type = 'restaurant' THEN restaurant.nom
                           WHEN reservation.type = 'voyage' THEN voyage.destination
                           WHEN reservation.type = 'billet' THEN billet.type
                       END AS name,
                       CASE
                           WHEN reservation.type = 'restaurant' THEN CONCAT('$', restaurant.prix)
                           WHEN reservation.type = 'voyage' THEN CONCAT('$', voyage.prix)
                           WHEN reservation.type = 'billet' THEN CONCAT('$', billet.prix)
                       END AS prix,
                       reservation.date_reservation,
                       utilisateur.nom AS user_nom,
                       utilisateur.prenom AS user_prenom,
                       COALESCE(
                           CASE
                               WHEN reservation.type = 'restaurant' THEN restaurant.reputation
                               WHEN reservation.type = 'voyage' THEN voyage.reputation
                               WHEN reservation.type = 'billet' THEN billet.reputation
                           END, 0
                       ) AS reputation
                FROM reservation
                LEFT JOIN restaurant ON reservation.restaurant_id = restaurant.id
                LEFT JOIN voyage ON reservation.voyage_id = voyage.id
                LEFT JOIN billet ON reservation.billet_id = billet.id_billet
                LEFT JOIN utilisateur ON reservation.user_id = utilisateur.id
                WHERE reservation.compte_id = $account_id";
        $result = $conn->query($sql);
        // Check if the query was successful
        if ($result === false) {
            // Display error message
            echo "Error: " . $conn->error;
        } elseif ($result->num_rows > 0) {
            // Initialize total price variable
            $total_price = 0;

            // Output the list of reservations in a table
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered'>";
            echo "<thead class='thead-dark'><tr><th>Reservation Type</th><th>Name</th><th>Date of Reservation</th><th>Prix</th><th>Reputation</th><th>Paiement</th></tr></thead>";
            echo "<tbody>";
            while($row = $result->fetch_assoc()) {
                // Create star rating based on reputation
                $stars = str_repeat('★', $row["reputation"]) . str_repeat('☆', 5 - $row["reputation"]);

                echo "<tr id='reservation_row_" . $row["id"] . "'><td>" . $row["type"]. "</td><td>" . $row["reservation_name"]."</td><td>" . $row["date_reservation"]. "</td><td>" . $row["prix"].  "</td><td class='star-rating'>" . $stars . "</td><td>
                <form id='form_" . $row["id"] . "' action='paiement.php' method='post'> <!-- Changed action to paiement.php and method to post -->
                    <input type='hidden' name='reservation_id' value='" . $row["id"] . "'>
                    <button type='submit' name='payer' class='btn btn-success rounded-pill'>Payer</button>
                </form>
                <form action='' method='post'>
                    <button type='submit' name='cancel_reservation' value='" . $row["id"] . "' class='btn btn-danger rounded-pill'>Cancel</button>
                </td></tr>";
                // Remove dollar sign and comma from price before adding to total
                $price = str_replace(['$', ','], '', $row["prix"]);
                // Add price to total
                $total_price += $price;
            }
            echo "</tbody>";
            // Display total price row
            echo "<tfoot><tr><td colspan='4'></td><td><strong>Total:</strong> $" . number_format($total_price, 2) . "</td><td></td></tr></tfoot>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "No reservations found for your account";
        }
        
        // Close database connection
        $conn->close();
    } else {
        // If user_id is not set in the session, display a message
        echo "No account ID found in session. Please log in first.";
    }
    ?>
            <div class="position-fixed bottom-0 end-0 p-3">
            <a href="client_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
</div>

<!-- Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
