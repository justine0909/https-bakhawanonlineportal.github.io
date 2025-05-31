<?php
include 'database_connection.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complete_name = htmlspecialchars(trim($_POST['complete_name']));
    $age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
    $gender = htmlspecialchars($_POST['gender']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $address = htmlspecialchars(trim($_POST['address']));
    $contact_number = htmlspecialchars(trim($_POST['contact_number']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if ($age === false || $age < 1) {
        $message = "<p style='color: red;'>Invalid age. Please enter a valid number.</p>";
    } elseif (stripos($address, "Bakhawan") === false) {
        $message = "<p style='color: red;'>Only residents of Barangay Bakhawan can register!</p>";
    } else {
        try {
            $check = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $check->bind_param("ss", $username, $email);
            $check->execute();
            $result = $check->get_result();

            if ($result->num_rows > 0) {
                $message = "<p style='color: red;'>Username or Email already exists!</p>";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (complete_name, age, gender, email, address, contact_number, username, password, role) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

             $role = 'user'; // Default role for new users
             $stmt->bind_param("sisssssss", $complete_name, $age, $gender, $email, $address, $contact_number, $username, $password, $role);
     
                if ($stmt->execute()) {
                    $message = "<p style='color: green;'>Registration successful! Redirecting to login...</p>";
                    header("refresh:2; login.php");
                } else {
                    $message = "<p style='color: red;'>Error: " . $stmt->error . "</p>";
                }
                $stmt->close();
            }
            $check->close();
        } catch (Exception $e) {
            $message = "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Delivery Registration</title>
    <style>
/* General styles */
body {
    font-family: Arial, sans-serif;
    background-image: url('image_login.jpg'); /* Replace with your image path */
    background-size: cover; /* Ensures the image covers the whole screen */
    background-position: center; /* Centers the image */
    background-repeat: no-repeat; /* Prevents the image from repeating */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}


/* Container */
.container {
    padding: 20px;
    border-radius: 15px;
    width: 550px;
    height: 90%;
    backdrop-filter: blur(50px); /* Blur effect */
    background: rgba(209, 208, 208, 0.42);
    background: linear-gradient(to bottom, #fff, #f1f1f1);
    transition: 0.3s ease-in-out;
    outline: none;
}

/* Headings */
h2 {
    text-align: center;
    color: black;
    margin-top: 1%;
    margin-bottom: 2%;
    font-size: 55px;
    font-weight: 900;
    padding: 7px;
    padding-bottom: 3%;
}

h3 {
    color:black;
    text-align: center;
    font-weight: bold;
    font-size: 35px;
    margin-bottom: 5%;
    margin-top: 2%;
}

.form-group {
    margin-bottom: 20px;
}

/* Labels */
label {
    display: none;
    font-weight: bold;
    margin-bottom: 5px;
}

input[type="text"],
input[type="email"],
input[type="password"],
input[type="select"],
input[type="number"] {
    padding: 13px;
    border: 1px solid #ccc;
    font-size: 14px;
    border-radius: 15px;
    width: 85%;
    height: 20px;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2), 
                -3px -3px 10px rgba(255, 255, 255, 0.5); /* Light and dark shadow for depth */
    background: linear-gradient(to bottom, #fff, #f1f1f1); /* Gradient for a raised look */
    transition: 0.3s ease-in-out;
    outline: none;
}

select {
    padding: 13px;
    border: 1px solid #ccc;
    font-size: 14px;
    border-radius: 15px;
    width: 95%;
    height: 48px;
    box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2), 
                -3px -3px 10px rgba(255, 255, 255, 0.5); /* Light and dark shadow for depth */
    background: linear-gradient(to bottom, #fff, #f1f1f1); /* Gradient for a raised look */
    transition: 0.3s ease-in-out;
    outline: none;
}

input:focus, select:focus, textarea:focus {
  border-color:rgb(37, 214, 1);
  box-shadow: 0 0 8px rgba(81, 255, 0, 0.3);
  outline: none;
}


.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

/* Button */
button {
    width: 50%;
    padding: 13px;
    background-color: #008000;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 1%;
    margin-left: 25%;
}

button:hover {
    background: #004D00;
}

/* Message Styling */
.message {
    text-align: center;
    margin-top: 10px;
    font-weight: bold;
}

.register-link {
    margin-top: 15px;
    font-size: 14px;
    color: #333;
    text-align: center;
}

.register-link a {
    color: purple;
    text-decoration: none;
    font-weight: bold;
}

.register-link a:hover {
    text-decoration: underline;
    color: #0056b3;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
<form action="register.php" method="POST">
    <div class="form-grid">
        <div class="form-group">
            <label for="complete_name">Complete Name</label>
            <input type="text" id="complete_name" name="complete_name" placeholder="Enter your complete name" required>
        </div> 

        <div class="form-group">
            <label for="age">Age</label>
            <input type="number" id="age" name="age" min="1" placeholder="Enter your age" required>
        </div>
    </div>
    <div class="form-grid">
        <div class="form-group">
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
    </div>
    <div class="form-grid">
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" id="address" name="address" placeholder="Enter your address" required>
        </div>
        <div class="form-group">
            <label for="contact_number">Contact Number</label>
            <input type="text" id="contact_number" name="contact_number" placeholder="Enter your contact number" required>
        </div>
    </div>

    <h3>Input Username and Password</h3>
    <div class="form-grid">
        <div class="form-group">
           <label for="username">Username</label>
           <input type="text" id="username" name="username" placeholder="Choose a username" required>
        </div>
        <div class="form-group">
           <label for="password">Password</label>
           <input type="password" id="password" name="password" placeholder="Create a password" required>
        </div>
    </div>
    <button type="submit">Register</button>
    <p style="text-align: center; margin-top: 20px;">
       Already have an account? <a href="login.php">Login here</a>
    </p>
    <div class="message"> <?php echo $message; ?> </div>
</form>

    </div>
</body>
</html>
