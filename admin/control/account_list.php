<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
            background-image: url('../../src/backgrd.png'); /* Replace 'path_to_your_image.jpg' with the actual path to your image */
            background-position: center;
            background-attachment: fixed; /* This ensures the background image stays fixed while scrolling */
      }
    h1 {
            color: white;
            font-style: italic;
        }
     .form-label {
        color: white !important; /* Set label and input text color to white */
    }

    .btn-primary {
        background-color: black; /* Set button background color to black */
        color: white; /* Set button text color to white */
        border: 1px solid black; /* Set button border color to black */
    }

    .btn-primary:hover {
        background-color: #333; /* Darken button background color on hover */
    }
    .container.mt-5 {
        color: white;
    }
    table,
    th,
    td {
        color: white !important; /* Set table text color to white */
        border-color: white !important; /* Set table borders color to white */
    }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Account List📋</h1>
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Telephone</th>
                            <th>Address</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Database connection
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "project";
                        $conn = new mysqli($servername, $username, $password, $dbname);

                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // SQL query to retrieve all accounts with their type
                        $sql = "SELECT utilisateur.id, compte.username, utilisateur.email, utilisateur.tel, utilisateur.adresse, 
                                CASE 
                                    WHEN agence.id IS NOT NULL THEN 'Agency' 
                                    WHEN client.id IS NOT NULL THEN 'Client' 
                                    ELSE 'Unknown' 
                                END AS type,
                                compte.status
                                FROM utilisateur 
                                INNER JOIN compte ON utilisateur.id = compte.utilisateur_id
                                LEFT JOIN agence ON utilisateur.id = agence.utilisateur_id
                                LEFT JOIN client ON utilisateur.id = client.utilisateur_id";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["id"] . "</td>";
                                echo "<td>" . $row["username"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["tel"] . "</td>";
                                echo "<td>" . $row["adresse"] . "</td>";
                                echo "<td>" . $row["type"] . "</td>";
                                echo "<td>";
                                if ($row["status"] === 'activated') {
                                    echo "<form action='deactivate_account.php' method='post'>";
                                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                                    echo "<button type='submit' class='btn btn-danger'>Deactivate❌</button>";   
                                    echo "</form>";    
                                } else {
                                    echo "<form action='activate_account.php' method='post'>";
                                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                                    echo "<button type='submit' class='btn btn-success'>Activate✅</button>";   
                                    echo "</form>";         
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No accounts found.</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Return to dashboard link -->
    <div class="container mt-3">
        <a href="../admin_dashboard.php" class="btn btn-primary">Return to Dashboard</a>
    </div>
</body>
</html>
