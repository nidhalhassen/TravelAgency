<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading...</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* Center the loading spinner and message */
        .loading-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .loading-message {
            margin-top: 20px;
            font-size: 18px;
        }
    </style>
    <!-- Redirect to invoice printing page after delay -->
    <script>
        setTimeout(function() {
            window.location.replace("print_invoice.php");
        }, 3000); // Redirect after 3 seconds (adjust as needed)
    </script>
</head>
<body>

<div class="loading-container">
    <!-- Loading spinner -->
    <div class="spinner-grow text-primary" role="status" style="width: 5rem; height: 5rem;">
        <span class="sr-only">Loading...</span>
    </div>
    <!-- Loading message -->
    <div class="loading-message">
        Loading...
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
