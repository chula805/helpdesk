<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <title>Dashboard</title>
    
    <style>
        /* Fade-in animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container h2 {
            animation: fadeIn 1.5s ease-in-out;
            background: linear-gradient(90deg, #192E41, #015D53, #52688F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }

        .container img {
            animation: fadeIn 2s ease;
            max-width: 100%;
            height: auto;
        }


    </style>
</head>
<body>
    <!-- Include the Navbar -->
    <?php include 'nav.php'; ?>

    <div class="container mt-5 text-center">
        <h2>Welcome to the Helpdesk System!</h2>
        <img src="assets/img/cover_1.jpg" alt="Description of the image" class="img-fluid my-4">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
