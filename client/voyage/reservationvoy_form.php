<?php
session_start();

if(isset($_POST['submit_reservation'])) {
    $user_id = $_SESSION['user_id'] ?? null;
    $compte_id = $user_id;
    $voyage_id = $_POST['voyage_id'] ?? null; // Assuming voyage_id is submitted from the form
    $date_reservation = date('Y-m-d H:i:s');
    $name = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $passport_number = $_POST['passport_number'] ?? null;

    if($compte_id === null) {
        echo "Error: compte_id is not set or null.";
        exit;
    }

    $conn = new mysqli("localhost", "root", "", "project");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $query = "INSERT INTO reservation (user_id, compte_id, type, voyage_id, date_reservation, name, email, passport_number) 
              VALUES ('$user_id', '$compte_id', 'voyage', '$voyage_id', '$date_reservation', '$name', '$email', '$passport_number')";

    if ($conn->query($query) === TRUE) {
        echo '<div class="alert alert-success" role="alert">Reservation successfully added.</div>';
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Voyage</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Custom styles -->
    <style>
        /* Centering the container vertically and horizontally */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        /* Additional styling for form */
        form {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 20px;
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
        <source src="../../src/background.mp4" type="video/mp4">
    </video>
    <div class="container">
        <div>
            <h1>Reservation Voyage</h1>
            <form method="post" action="">
                <!-- Include a hidden field for the voyage ID -->
                <input type="hidden" name="voyage_id" value="<?php echo $_SESSION['voyage_id'] ?? ''; ?>">
                
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                </div>
                
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                </div>
                
                <div class="form-group">
                    <input type="text" name="passport_number" class="form-control" placeholder="Passport Number" required>
                </div>
                
                <button type="submit" name="submit_reservation" class="btn btn-primary btn-block">Submit Reservation</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS (optional if you need JavaScript functionality) -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>



