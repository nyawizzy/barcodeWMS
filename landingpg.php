<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link {
            color: #29ab87 !important; /* link color */
        }

        .nav-link:hover {
            color: #ffc300 !important; /* hover color */
        }

        .content {
            padding: 30px;
        }

        .list-group-item {
            border-color: #ddd; /* border color */
        }

        .login {
            margin-top: 50px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="subheader text-center">
            SAVANNAH EVERGREEN NAIROBI
        </div>
        <div class="container">
            <h1 class="text-center">Warehouse Management System</h1>
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="manager_dashboard.php">Manager Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="staff_dashboard.php">Staff Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a></li>
                            
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <main class="content">
        <p>Welcome to the SE Management System.</p>
        <p>This web application is designed to streamline the process of managing warehouse operations for Savannah Evergreen. With this system, you can:</p>
        <ul class="list-group">
            <li class="list-group-item">Manage inventory</li>
            <li class="list-group-item">Track location of inventory</li>
            <li class="list-group-item">Manage orders</li>
            <li class="list-group-item">Generate reports</li>
            <li class="list-group-item">Integrate barcode scanning for operations</li>
        </ul>

        <section class="login">
            <h2>New user? Register here</h2>
            <div class="d-flex justify-content-center">
                <a href="signupp.php" class="btn btn-outline-primary ms-2" style="color: #ffc300; background-color: #800000;">Register</a>
            </div>
        </section>
    </main>

    <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
        <p class="text-center">&copy; 2024 Savannah Evergreen</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


