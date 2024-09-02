<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <title>Login Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 50px;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 50px;
        }
        
        form {
            background-color: #800000;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            margin: 0 auto;
            max-width: 400px;
            color: #fff; 
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            box-sizing: border-box;
        }
        
        input[type="submit"] {
            background-color: #ffc300;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        
        #errorMessage {
            color: #f00;
            font-weight: bold;
            margin-top: 10px;
        }
        
        .forgotPassword {
            text-align: center;
            margin-top: 20px;
            font-size: 0.8em;
        }
        
        .forgotPassword a {
            color: rgb(67, 67, 226);
            text-decoration: none;
        }
    </style>
</head>
<body>
    <header style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="container text-center">
            <h1 style="font-size: 24px;">SAVANNAH EVERGREEN</h1>
        </div>
    </header>
    
    <div class="container mt-5">
        <h1 style="color: black;">Login</h1>
        <form id="loginForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="employee_no">Employee Number:</label><br>
            <input type="text" id="employee_no" name="employee_no" required><br>
            <label for="role">Role:</label><br>
            <select id="role" name="role" required>
                <option value="Manager">Manager</option>
                <option value="Staff">Staff</option>
                <option value="System Admin">System Admin</option>
            </select><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" name="login" value="Log in">
        </form>
        <p id="errorMessage" style="color:red;"></p>
        <div class="forgotPassword">
            <p><a href="signupp.php">Register</a> | <a href="forgotpswd.php">Forgot your password?</a></p>
        </div>
    </div>

    <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="container text-center">
            <p style="font-size: 14px;">&copy; 2024 Savannah Evergreen</p>
        </div>
    </footer>

    <?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "Munga@123";
    $dbname = "wms";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
        $employee_no = $_POST["employee_no"];
        $role = $_POST["role"];
        $password = $_POST["password"];

        // Prepare SQL statement to fetch user from database
        $sql = "SELECT * FROM users WHERE employee_no = ? AND role = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $employee_no, $role);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];

                // Redirect user based on role
                $roles = array(
                    "Manager" => "manager_dashboard.php",
                    "Staff" => "staff_dashboard.php",
                    "System Admin" => "admin_dashboard.php"
                );
                $dashboard = $roles[$role];
                header("Location: $dashboard");
                exit();
            } else {
                echo '<script>alert("Invalid credentials");</script>';
            }
        } else {
            echo '<script>alert("Invalid credentials");</script>';
        }

        $stmt->close();
        $conn->close();
    }
    ?>

</body>
</html>



