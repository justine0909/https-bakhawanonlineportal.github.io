<?php
session_start();
include 'database_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch only Pending and Approved appointments
$query = "SELECT * FROM appointments WHERE user_id = ? AND status IN ('Pending', 'Approved')";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $appointments = []; // <--- make sure initialized to empty array
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appointments</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 95%;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            margin-left: 1%;
            margin-top: 2%;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #003500;
            color: white;
            font-size: 16px;
        }

        td {
            background-color: #f9f9f9;
            font-size: 14px;
        }

        .button {
            padding: 5px;
            background-color: green;
            color: white;
            text-decoration: none;
            font-size: 14px;
            padding: 8px;
            border: none;
        }

        .button:hover {
            background-color: #008000;
        }

        .view-proof {
           display: inline-block;
           padding: 8px;
           border-radius: 2px;
           background-color: #1e90ff;
           color: white;
           text-decoration: none;
           font-size: 14px;
           transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .view-proof:hover {
           background-color: #0066cc;
           transform: scale(1.05);
        }

        .hidden {
            display: none;
        }

        p {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Your Appointments</h2>

    <?php if (count($appointments) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Pansion</th>
                    <th>Location</th>
                    <th>Service</th>
                    <th>Payment Method</th>
                    <th>Appointment Date/Time</th>
                    <th>Price</th>
                    <th>Proof Picture</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($appointments as $appointment): ?>
        <tr>
            <td><?php echo htmlspecialchars($appointment['pansion_name']); ?></td>
            <td><?php echo htmlspecialchars($appointment['location']); ?></td>
            <td><?php echo htmlspecialchars($appointment['service']); ?></td>
            <td><?php echo htmlspecialchars($appointment['payment_method']); ?></td>
            <td><?php echo htmlspecialchars($appointment['appointment_datetime']); ?></td>
            <td>₱<?php echo number_format($appointment['price'], 2); ?></td>
            <td>
                <?php if ($appointment['proof_picture']): ?>
                    <a href="<?php echo htmlspecialchars($appointment['proof_picture']); ?>" target="_blank" class="view-proof">View Proof</a>
                <?php else: ?>
                    No Proof Provided
                <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($appointment['status']); ?></td>
            <td>
                <?php if ($appointment['status'] == 'Pending'): ?>
                    <form method="POST" action="user_cancel_appointment.php" onsubmit="return confirm('Are you sure you want to cancel this appointment?');">
                        <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                        <button type="submit" class="button">Cancel</button>
                    </form>
                <?php elseif ($appointment['status'] == 'Approved'): ?>
                    <button class="button hidden">Cancel</button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
        </table>
    <?php else: ?>
        <p>No appointments found.</p>
    <?php endif; ?>
</div>

</body>
</html>
