<?php
session_start();
include 'database_connection.php'; // Include the database connection

// Handle form submission
if (isset($_POST['book_appointment'])) {
    // Sanitize and retrieve form data
    $user_id = $_SESSION['user_id']; // Make sure the user is logged in and their user_id is stored in session
    $pansyon_name = $_POST['pansion_name'];
    $location = $_POST['location'];
    $service = $_POST['service'];
    $appointment_datetime = $_POST['datetime'];

    // Calculate price based on the selected service
    $price = 0;
    if ($service == 'package') {
        $price = 500; // Package Price
    } elseif ($service == 'single service') {
        $price = 300; // Single Service Price
    }

    // Insert data into the database (without payment yet)
    $stmt = $conn->prepare("INSERT INTO appointments 
        (user_id, pansion_name, location, service, price, appointment_datetime, status)
        VALUES (?, ?, ?, ?, ?, ?, 'Pending')
    ");
    $stmt->bind_param(
        "isssds", 
        $user_id, 
        $pansyon_name, 
        $location, 
        $service, 
        $price, 
        $appointment_datetime
    );

    if ($stmt->execute()) {
        // Get last inserted ID
        $appointment_id = $conn->insert_id;

        // Redirect to payment page
        header("Location: payment.php?appointment_id=$appointment_id");
        exit;
    } else {
        echo "<script>alert('Failed to book appointment. Please try again.');</script>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Cemetery Maintenance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .form-container {
            width: 60%;
            height: 550px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 2%;
        }

        .form-container input, .form-container textarea {
            padding: 15px;
            border: 1px solid #ccc;
            font-size: 14px;
            border-radius: 15px;
            width: 85%;
            height: 20px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2), 
                       -3px -3px 10px rgba(255, 255, 255, 0.5);
            background: linear-gradient(to bottom, #fff, #f1f1f1);
            transition: 0.3s ease-in-out;
            outline: none;
            margin-bottom: 20px;
        }

        .form-container select {
            padding: 15px;
            border: 1px solid #ccc;
            font-size: 14px;
            border-radius: 15px;
            width: 95%;
            height: 52px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2), 
                       -3px -3px 10px rgba(255, 255, 255, 0.5);
            background: linear-gradient(to bottom, #fff, #f1f1f1);
            transition: 0.3s ease-in-out;
            outline: none;
        }

        .form-grid {
           display: grid;
           grid-template-columns: 1fr 1fr;
           gap: 10px;
        }

        .form-group {
           margin-bottom: 20px;
        }

        .form-container button {
            width: 50%;
            padding: 13px;
            background-color: #008000;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 5%;
            margin-left: 25%;
        }

        .form-container button:hover {
            background-color:  #004D00;
        }

        h2 {
            text-align: center;
            color: black;
            margin-bottom: 20px;
            font-weight: 900;
            font-size: 45px;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            padding-bottom: 15px;
            border-bottom: 2px solid #ccc;
            margin-top: 20px;
        }

        .form-container label {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .form-container textarea {
            resize: vertical;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Book Cemetery Maintenance</h2>
    <form action="" method="POST" enctype="multipart/form-data">
<div class="form-grid">
    <div class="form-group">  
        <label for="pansion_name">Pansion</label>
        <input type="text" id="pansion_name" name="pansion_name" placeholder="Ibutang ang pangalan sa pansyon" required>
    </div>
    <div class="form-group"> 
        <label for="location">Location:</label>
        <input type="text" id="location" name="location" placeholder="Asa dapit" required>
    </div>
</div>

<div class="form-grid"> 
   <div class="form-group"> 
        <label for="service">Service</label>
        <select name="service" id="service" required onchange="updatePrice()">
            <option value="package">Package</option>
            <option value="single service">Single Service</option>
        </select>
   </div>
   <div class="form-group"> 
        <label for="datetime">Date and Time</label>
        <input type="datetime-local" id="datetime" name="datetime" required>
   </div>
</div>
<div class="form-group"> 
        <label for="price">Price / Presyo: <span id="price-display">₱500</span></label>
</div>
        <button type="submit" name="book_appointment">Book Appointment</button>
    </form>
</div>

<script>
    function updatePrice() {
        var service = document.getElementById("service").value;
        var priceDisplay = document.getElementById("price-display");

        // Update price based on service selection
        if (service === "package") {
            priceDisplay.innerText = "₱500";
        } else if (service === "single service") {
            priceDisplay.innerText = "₱300";
        }
    }
</script>

</body>
</html>
