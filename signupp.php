<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <title>Register Page</title>
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
     
    input[type="text"], input[type="password"], input[type="email"], select {
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
  </style>
</head>
<body>
  <header style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div class="container text-center">
      <h1 style="font-size: 24px;">SAVANNAH EVERGREEN</h1>
    </div>
  </header>
   
  <div class="container mt-5">
    <h1 style="color: black;">Register</h1>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

      $fullName = $_POST["fullName"];
      $employeeNumber = $_POST["employeeNumber"];
      $email = $_POST["email"];
      $role = $_POST["role"];
      $password = $_POST["password"];
      $confirmPassword = $_POST["confirmPassword"];

      // Validate passwords match
      if ($password !== $confirmPassword) {
        echo "Passwords do not match!";
      } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // SQL to insert data into the database
        $sql = "INSERT INTO users (full_name, employee_no, email, role, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $fullName, $employeeNumber, $email, $role, $hashedPassword);

        if ($stmt->execute() === TRUE) {
          header("Location: loginpage.php");
          exit();
        } else {
          echo "Error: " . $stmt->error;
        }

        $stmt->close();
      }

      $conn->close();
    }
    ?>

    <form id="RegisterForm" method="post">
      <label for="fullName">Full Name:</label><br>
      <input type="text" id="fullName" name="fullName" required><br>
      <label for="employeeNumber">Employee Number:</label><br>
      <input type="text" id="employeeNumber" name="employeeNumber" required><br>
      <label for="email">Email:</label><br>
      <input type="email" id="email" name="email" required><br>
      <label for="role">Role:</label><br>
      <select id="role" name="role" required>
        <option value="Manager">Manager</option>
        <option value="Staff">Staff</option>
        <option value="System Admin">System Admin</option>
      </select><br>
      <label for="password">Password:</label><br>
      <input type="password" id="password" name="password" required><br>
      <label for="confirmPassword">Confirm Password:</label><br>
      <input type="password" id="confirmPassword" name="confirmPassword" required><br>
      <input type="submit" value="Register">
    </form>
     
      <p style="text-align: center;">
        Already have an account? <a href="loginpage.php">Log in</a>
      </p>

  </div>

  <footer style="background-color: #800000; color: #ffc300; padding: 10px;">
    <div class="container text-center">
      <p style="font-size: 14px;">&copy; 2024 Savannah Evergreen</p>
    </div>
  </footer>
   
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   
  <p id="errorMessage" style="color: red;"></p>

  <script>
    document.getElementById("RegisterForm").addEventListener("submit", function(event) {
      var fullName = document.getElementById("fullName").value;
      var employeeNumber = document.getElementById("employeeNumber").value;
      var email = document.getElementById("email").value;
      var password = document.getElementById("password").value;
      var confirmPassword = document.getElementById("confirmPassword").value;
      var errorMessage = document.getElementById("errorMessage");

      if (fullName === "" || employeeNumber === "" || email === "" || password === "" || confirmPassword === "") {
        errorMessage.textContent = "Please fill in all fields.";
        event.preventDefault();
      } else if (password !== confirmPassword) {
        errorMessage.textContent = "Passwords do not match.";
        event.preventDefault();
      } else if (!isValidEmail(email)) {
        errorMessage.textContent = "Please enter a valid email address.";
        event.preventDefault();
      }
    });

    function isValidEmail(email) {
      var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailPattern.test(email);
    }
  </script>
</body>
</html>
