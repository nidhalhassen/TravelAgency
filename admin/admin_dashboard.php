<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .centered {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .button-group {
            text-align: center;
        }
        body {
            background-image: url('../src/background.jpg'); /* Replace 'path_to_your_image.jpg' with the actual path to your image */
            background-position: center;
            background-attachment: fixed; /* This ensures the background image stays fixed while scrolling */
        }
        h1 {
            color: white;
            font-style: italic;
        }
        .button-group .btn {
            background-color: black;
            color: white;
            border: 1px solid black;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.3); /* Adding a black shadow */
            padding: 15px 30px; /* Adjust padding for bigger button size */
            font-size: 20px; /* Adjust font size for bigger button */
        }

        .button-group .btn:hover {
            background-color: #333; /* Darken the background on hover */
        }
    </style>
</head>
<body>
    <div class="container">
    <h1 class="text-center mb-4">Admin Dashboard⚙️</h1>
        <div class="row">
            <div class="col">
                <div class="centered">
                    <div class="button-group">
                        <div class="mb-3">
                            <a href="SearchAcc.php" class="btn btn-primary">Search   Account🔎</a>
                        </div>
                        <div class="mb-3">
                            <a href="control/account_list.php" class="btn btn-primary">Show Account List📋</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
