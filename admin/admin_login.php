<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            background-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.5);
            border: none;
            border-radius: 15px; /* Rounded corners */
            padding: 15px; /* Padding */
            box-shadow: none; /* Remove shadow */
        }
        .welcome-message {
            margin-top: 20px;
            text-align: center;
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
        .create-account-link {
            color: black;
        }
    </style>
</head>
<body>
    <video autoplay loop muted id="video-background">
        <source src="../src/background.mp4" type="video/mp4">
    </video>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Admin Login🛠️</h2>
                        <form method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" name="submit">Login</button>
                        </form>
                        <?php
                            if(isset($_POST["submit"])){
                                $useradmin=$_POST["username"];
                                $passadmin=$_POST["password"];
                                if(($useradmin=='admin')&&($passadmin=='admin')){
                                    echo "<div class='welcome-message'>✅Welcome back, " . $useradmin . "!</div>";
                                    echo '<script>
                                    setTimeout(function() {
                                        window.location.href = "admin_dashboard.php";
                                    }, 2000);
                                    </script>';
                                }
                                else{
                                    echo "<div style='margin-top: 20px; text-align: center;'>❌Invalid username or password.</div>";
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
