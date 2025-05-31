<?php
session_start();
include 'database_connection.php';

if (!isset($_GET['appointment_id'])) {
    die("Invalid access!");
}

$appointment_id = intval($_GET['appointment_id']);

if (isset($_POST['submit_payment'])) {
    $payment_method = $_POST['payment_method'];

    // Handle file upload
    if (isset($_FILES['proof_picture']) && $_FILES['proof_picture']['error'] === UPLOAD_ERR_OK) {
        $proof_picture = $_FILES['proof_picture']['name'];
        $target_dir = "uploads/";
        $proof_picture_filename = time() . "_" . basename($proof_picture);
        $proof_picture_path = $target_dir . $proof_picture_filename;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES['proof_picture']['tmp_name'], $proof_picture_path)) {
            // Update the appointment with payment_method and proof_picture
            $stmt = $conn->prepare("UPDATE appointments SET payment_method = ?, proof_picture = ? WHERE id = ?");
            $stmt->bind_param("ssi", $payment_method, $proof_picture_path, $appointment_id);

            if ($stmt->execute()) {
                echo "<script>alert('Payment submitted successfully!'); window.location.href='user_book_appointment.php';</script>";
            } else {
                echo "<script>alert('Failed to submit payment.');</script>";
            }
        } else {
            echo "<script>alert('Failed to upload proof picture.');</script>";
        }
    } else {
        echo "<script>alert('Proof picture is required!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Payment</title>
</head>

<style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .payment-container {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        select, input[type="file"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            width: 100%;
        }
        button {
            padding: 12px;
            background-color: #008000;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #004D00;
        }
</style>

<body>
    <div class="payment-container">
        <h2>Submit Your Payment</h2>
        <p>Gcash #: 09702222461</p>
        <p>Gcash name: Jerick Rosacena</p>
        <form method="post" enctype="multipart/form-data">
            <label for="payment_method">Payment Method</label>
            <select name="payment_method" id="payment_method" required>
                <option value="gcash">GCash</option>
                <option value="cod">COD</option>
            </select>

            <label>Upload Proof of Payment:</label>
            <input type="file" name="proof_picture" required><br><br>

            <button type="submit" name="submit_payment">Submit Payment</button>
        </form>
</body>
</html>
