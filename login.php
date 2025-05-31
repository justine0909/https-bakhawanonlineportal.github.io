<?php
session_start();

// Ensure the session is active before regenerating the ID
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

include 'database_connection.php'; // Database connection

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$error = ""; // Variable to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    // Prevent SQL Injection
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password, $role);
        $stmt->fetch();

        // Verify password
        if ($hashed_password && password_verify($password, $hashed_password)) {
            // Set session variables securely
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            // Role-Based Redirection
            if ($role === 'admin') {
                header("Location: admin.php");
                exit(); // Prevent further script execution
            } else {
                header("Location: user.php");
                exit(); // Prevent further script execution
            }
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('image_login.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            height: 65%;
            text-align: center;
            backdrop-filter: blur(50px); /* Blur effect */
            background: rgba(209, 208, 208, 0.42); /* Semi-transparent white */
            background: linear-gradient(to bottom, #fff, #f1f1f1);
            transition: 0.3s ease-in-out;
            outline: none;
        }

        h2 {
            color: black;
            margin-top: 4%;
            margin-bottom: 4%;
            font-size: 55px;
            font-weight: 900;
            padding: 7px;
            padding-bottom: 7%;
            border-bottom: 1px solid black;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        input {
            width: calc(98% - 40px);
            padding: 15px 10px 10px 35px;
            border: 1px solid #ccc;
            border-radius: 15px;
            margin-top: 5px;
            height: 20px;
            font-size: 14px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2), 
                -3px -3px 10px rgba(255, 255, 255, 0.5); 
            background: linear-gradient(to bottom, #fff, #f1f1f1);
            transition: 0.3s ease-in-out;
            outline: none;
        }

        input:focus {
            border-color:rgb(37, 214, 1);
            box-shadow: 0 0 8px rgba(81, 255, 0, 0.3);
            outline: none;
        }

        input::placeholder {
            color: #aaa;
        }

        button {
            width: 100%;
            padding: 13px;
            background-color: #008000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        button:hover {
            background: #004D00;
        }

        .register-link {
            margin-top: 15px;
            font-size: 14px;
            color: #333;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .register-link a:hover {
            text-decoration: underline;
            color: #0056b3;
        }

        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>

       <div style="text-align: right; margin-top: -7px;">
               <a href="forgot_password.php" style="font-size: 14px; color: #2e7d32; text-decoration: none;">Forgot Password?</a>
       </div>


            <button type="submit">Login</button>
            <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
            <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?> 
        </form>
    </div>
</body>
</html>
