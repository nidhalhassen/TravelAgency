<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* CSS styles for the invoice */
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .total-row {
            font-weight: bold;
        }
        .invoice-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-logo img {
            max-width: 200px;
            height: auto;
        }
        .print-btn {
            text-align: center;
        }
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="invoice-logo">
        <img src="../images/logo.png" alt="Company Logo">
    </div>
    <h2>Invoice</h2>

    <?php
    session_start();

    // Check if user_id is set in the session
    if(isset($_SESSION['user_id'])) {
        // Retrieve the account ID from the session
        $account_id = $_SESSION['user_id'];

        // Check if payment information is received via POST
        if(isset($_POST['reservation_id'])) {
            // Retrieve payment and reservation information from the POST request
            $reservation_id = $_POST['reservation_id'];
            $payment_date = date("Y-m-d H:i:s"); // Current date and time

            // Connect to the database and retrieve reservation details
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
                echo "<p>No reservation found with the provided ID.</p>";
            } else {
                // Output reservation details
                $row = $result->fetch_assoc();
    ?>

                <table>
                    <tr>
                        <th>Reservation ID:</th>
                        <td><?php echo $row["id"]; ?></td>
                    </tr>
                    <tr>
                        <th>Type:</th>
                        <td><?php echo $row["type"]; ?></td>
                    </tr>
                    <tr>
                        <th>Reservation Name:</th>
                        <td><?php echo $row["reservation_name"]; ?></td>
                    </tr>
                    <tr>
                        <th>Name:</th>
                        <td><?php echo $row["name"]; ?></td>
                    </tr>
                    <tr>
                        <th>Date of Reservation:</th>
                        <td><?php echo $row["date_reservation"]; ?></td>
                    </tr>
                    <!-- Add more reservation details as needed -->

                    <tr class="total-row">
                        <th>Total Amount:</th>
                        <td><?php echo htmlspecialchars($row['prix']); ?></td>
                    </tr>
                </table>

                <p>Payment Date: <?php echo $payment_date; ?></p>
                <!-- Add more payment details as needed -->

    <?php
            }

            // Close database connection
            $conn->close();
        } else {
            echo "<p>No payment details received.</p>";
        }
    } else {
        // If user_id is not set in the session, display a message
        echo "No account ID found in session. Please log in first.";
    }
    ?>
            <div class="position-fixed bottom-0 end-0 p-3">
            <a href="client_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
</div>

<div class="print-btn">
    <button class="btn btn-primary no-print" onclick="window.print()">Print</button>
</div>

</body>
</html>
