<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
        body {
            background-image: url('../src/backgrd.png'); /* Replace 'path_to_your_image.jpg' with the actual path to your image */
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
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Search Account</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="post">
                    <div class="mb-3">
                        <label for="searchType" class="form-label">Search Type</label>
                        <select class="form-select" id="searchType" name="searchType" required>
                            <option value="id">ID</option>
                            <option value="name">Name</option>
                            <option value="tel">Telephone Number</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="searchQuery" class="form-label">Search</label>
                        <input type="text" class="form-control" id="searchQuery" name="searchQuery" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve search type and query
        $searchType = $_POST["searchType"];
        $searchQuery = $_POST["searchQuery"];

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

        // Prepare SQL statement based on search type
        switch ($searchType) {
            case 'id':
                $sql = "SELECT * FROM utilisateur WHERE id = '$searchQuery'";
                break;
            case 'name':
                $sql = "SELECT * FROM utilisateur WHERE nom LIKE '%$searchQuery%' OR prenom LIKE '%$searchQuery%'";
                break;
            case 'tel':
                $sql = "SELECT * FROM utilisateur WHERE tel = '$searchQuery'";
                break;
            default:
                echo "Invalid search type";
                exit;
        }

        // Execute SQL query
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<div class='container mt-5'>";
            echo "<h2 class='text-center mb-4'>Search Results📋</h2>";
            echo "<div class='row'>";
            echo "<div class='col'>";
            echo "<table class='table'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Email</th>";
            echo "<th>Telephone Number</th>";
            echo "<th>Address</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["nom"] . " " . $row["prenom"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["tel"] . "</td>";
                echo "<td>" . $row["adresse"] . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        } else {
            echo "<div class='container mt-5'>";
            echo "<h2 class='text-center'>No results found</h2>";
            echo "</div>";
        }

        $conn->close();
    }
    ?>
    <div class="container mt-3">
        <a href="admin_dashboard.php" class="btn btn-primary">Return to Dashboard</a>
    </div>
</body>
</html>
