<?php
session_start();
include 'database_connection.php'; // your database connection file

// Variables
$message = "";
$step = 1; // Step 1: Ask for complete name and email

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['complete_name']) && isset($_POST['email'])) {
        $complete_name = htmlspecialchars(trim($_POST['complete_name']));
        $email = htmlspecialchars(trim($_POST['email']));

        // Check if both complete name and email exist together
        $stmt = $conn->prepare("SELECT id FROM users WHERE complete_name = ? AND email = ?");
        $stmt->bind_param("ss", $complete_name, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['reset_email'] = $email;
            $step = 2; // Proceed to reset password
        } else {
            $message = "❌ No matching account found with that complete name and email.";
        }
        $stmt->close();
    }

    if (isset($_POST['new_password']) && isset($_SESSION['reset_email'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hashed_password, $_SESSION['reset_email']);
            
            if ($stmt->execute()) {
                $message = "✅ Password reset successful! You can now <a href='login.php' class='login-link'><strong>login</strong></a>.";
                unset($_SESSION['reset_email']);
                $step = 3; // Password changed
            } else {
                $message = "❌ Error resetting password.";
            }
            $stmt->close();
        } else {
            $message = "❌ Passwords do not match!";
            $step = 2; // Stay in step 2
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('image_login.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            width: 400px;
            margin-top: 10%;
        }

        .form-container h2 {
            color: black;
            margin-top: 4%;
            margin-bottom: 4%;
            font-size: 30px;
            font-weight: 900;
            padding: 7px;
            padding-bottom: 7%;
            border-bottom: 1px solid black;
        }

        .form-container input {
            width: 93%;
            padding: 13px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-top: 1%;
        }
        .form-container button {
            width: 100%;
            padding: 13px;
            background-color: #008000;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 2%;
            margin-bottom: 2%;
        }
        .form-container button:hover {
            background-color: #004D00;
        }
        .message {
            margin-top: 15px;
            color: red;
        }
        .success {
            color: green;
        }
        a {
            color: #2d6a4f;
            text-decoration: none;
        }

        .login-link {
           color: green;
           text-decoration: none;
           font-weight: 900;
           transition: color 0.3s;
           font-size: 18px;
        }

.login-link:hover {
    color: darkgreen;
    text-decoration: underline;
}
    </style>
</head>
<body>

<div class="form-container">
    <?php if ($step == 1): ?>
        <h2>Forgot Password?</h2>
        <form method="POST">
            <input type="text" name="complete_name" placeholder="Enter Your Complete Name" required>
            <input type="email" name="email" placeholder="Enter Your Email Address" required>
            <button type="submit">Submit</button>
            <a class="back-link" href="login.php">← Back to Login</a>
        </form>
    <?php elseif ($step == 2): ?>
        <h2>Reset Your Password</h2>
        <form method="POST">
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            <button type="submit">Reset Password</button>
        </form>
    <?php elseif ($step == 3): ?>
        <h2 class="success">Password Reset!</h2>
        <p class="success"><?php echo $message; ?></p>
    <?php endif; ?>

    <?php if (!empty($message) && $step != 3): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
</div>

</body>
</html>
