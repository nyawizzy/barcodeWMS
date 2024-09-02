<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

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
            color: #f2f2f2;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="email"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color:  #ffc300;
            color:white;
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
    </style>
</head>
<body>
    <header style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="container text-center">
            <h1 style="font-size: 24px;">Forgot Password</h1>
        </div>
    </header>

    <div class="container mt-5">
        <!-- Forgot password form content here -->
        <form id="forgotPasswordForm" method="post">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br>
            <input type="submit" value="Reset Password" class="btn btn-primary mt-3">
        </form>
        <p id="errorMessage" class="text-danger">
            <?php
            // PHP code for handling form submission
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Validate email field
                if (!empty($_POST["email"])) {
                    $email = $_POST["email"];
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        // Replace this part with actual password retrieval and email sending code
                        // For demonstration purpose, just displaying a message
                        echo "Check email for password!";
                    } else {
                        echo "Please enter a valid email address.";
                    }
                } else {
                    echo "Email field is required.";
                }
            }
            ?>
        </p>
    </div>

    <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
        <div class="container text-center">
            <p style="font-size: 14px;">&copy; 2024 Savannah Evergreen</p>
        </div>
    </footer>

    <script>
        
        var forgotPasswordForm = document.getElementById("forgotPasswordForm");
        var email = document.getElementById("email");
        var errorMessage = document.getElementById("errorMessage");

        forgotPasswordForm.addEventListener("submit", function(event) {
            event.preventDefault();

            if (!validateEmail(email.value)) {
                errorMessage.textContent = "Please enter a valid email address.";
            } else {
                errorMessage.textContent = "";
                // Send reset password request
                alert("Check email for password!");
            }
        });

        function validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }
    </script>
    <?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "Water100#";
$dbname = "infirmary_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted email
    $email = $_POST['email'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Prepare SQL statement to retrieve password based on email
        $sql = "SELECT password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Email found, fetch password and send it to the user's email
            $row = $result->fetch_assoc();
            $password = $row["password"];
            $to = $email;
            $subject = 'Your Forgotten Password';
            $message = 'Your password: ' . $password;
            $headers = 'From: your_email@example.com';

            // Send email
            if (mail($to, $subject, $message, $headers)) {
                $success_message = "Password sent to your email.";
            } else {
                $error = "Failed to send password email.";
            }
        } else {
            // Email not found
            $error = "Email not found.";
        }

        // Close prepared statement
        $stmt->close();
    }
}

// Close database connection
$conn->close();
?>
</body>
</html>