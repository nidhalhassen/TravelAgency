<!DOCTYPE html>
<html>
<head>
    <title>Payment Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<div class="container">
    <h2 class="mt-4"><i class="fas fa-credit-card"></i> Payment Details</h2>

    <?php
    // Start the session
    session_start();

    // Check if user_id is set in the session
    if(isset($_SESSION['user_id'])) {
        // Retrieve the account ID from the session
        $account_id = $_SESSION['user_id'];

        // Connect to the database
        // Replace the database credentials with your own
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "project";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if reservation details are received via POST
        if(isset($_POST['reservation_id'])) {
            // Retrieve reservation details from the POST request
            $reservation_id = $_POST['reservation_id'];

            // Prepare SQL query to fetch reservation details by ID
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
                   utilisateur.prenom AS user_prenom
            FROM reservation
            LEFT JOIN restaurant ON reservation.restaurant_id = restaurant.id
            LEFT JOIN voyage ON reservation.voyage_id = voyage.id
            LEFT JOIN billet ON reservation.billet_id = billet.id_billet
            LEFT JOIN utilisateur ON reservation.user_id = utilisateur.id
            WHERE reservation.compte_id = $account_id AND reservation.id = $reservation_id";

            $result = $conn->query($sql);

            if ($result === false || $result->num_rows == 0) {
                echo "<p class='mt-4'>No reservation found with the provided ID.</p>";
            } else {
                // Output reservation details
                $row = $result->fetch_assoc();
    ?>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="far fa-file-alt"></i> Reservation Details</h5>
                        <p class="card-text"><strong>Reservation ID:</strong> <?php echo $row["id"]; ?></p>
                        <p class="card-text"><strong>Type:</strong> <?php echo $row["type"]; ?></p>
                        <p class="card-text"><strong>Name:</strong> <?php echo $row["name"]; ?></p>
                        <p class="card-text"><strong>Date of Reservation:</strong> <?php echo $row["date_reservation"]; ?></p>
                        <p class="card-text"><strong>Price:</strong> <?php echo htmlspecialchars($row['prix']); ?></p>
                    </div>
                </div>

                <h3 class="mt-4"><i class="fas fa-credit-card"></i> Enter Payment Information</h3>
                <form id="paymentForm" action="print_invoice.php" method="post" class="mt-3">
                    <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>">
                    <div class="form-group">
                        <label for="card_number"><i class="far fa-credit-card"></i> Card Number:</label>
                        <input type="text" id="card_number" name="card_number" class="form-control" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="expiry_date"><i class="far fa-calendar-alt"></i> Expiry Date:</label>
                            <input type="text" id="expiry_date" name="expiry_date" class="form-control" placeholder="MM/YY" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cvv"><i class="fas fa-lock"></i> CVV:</label>
                            <input type="text" id="cvv" name="cvv" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name_on_card"><i class="far fa-user"></i> Name on Card:</label>
                        <input type="text" id="name_on_card" name="name_on_card" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email"><i class="far fa-envelope"></i> Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" id="submitPayment" class="btn btn-primary"><i class="fas fa-dollar-sign"></i> Submit Payment</button>
                </form>

    <?php
            }

            // Close database connection
            $conn->close();
        } else {
            echo "<p class='mt-4'>No reservation details received.</p>";
        }
    } else {
        // If user_id is not set in the session, display a message
        echo "No account ID found in session. Please log in first.";
    }
    ?>
</div>

<!-- Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
