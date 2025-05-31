<?php
session_start();
include 'database_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if user is searching with a date filter
$filter_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : null;

// Base query
$query = "
    SELECT u.complete_name AS client_name, a.service, a.appointment_datetime, a.proof_image, a.rating, a.feedback, c.name AS pansion_name
    FROM appointments AS a
    INNER JOIN users AS u ON a.user_id = u.id
    INNER JOIN cleaners AS c ON a.cleaner_id = c.id
    WHERE a.user_id = ? AND a.status = 'Complete'
";

// If there's a filter date, add it
if (!empty($filter_date)) {
    $query .= " AND DATE(a.appointment_datetime) = ?";
}

$stmt = $conn->prepare($query);

// Bind parameters
if (!empty($filter_date)) {
    $stmt->bind_param("is", $user_id, $filter_date); // user_id (i), filter_date (s)
} else {
    $stmt->bind_param("i", $user_id); // only user_id
}

$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
if ($result && $result->num_rows > 0) {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            margin: 20px;
        }

        h2 {
            text-align: center;
            color: black;
            margin-bottom: 10px;
            font-weight: 900;
            font-size: 40px;
            text-transform: uppercase;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            padding-bottom: 15px;
            border-bottom: 2px solid #ccc;
            margin-top: 40px;
        }

        table {
            margin-top: -3%;
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #003500;
            color: white;
        }
        img.proof {
            width: 100px;
            height: auto;
            border-radius: 8px;
        }
        
        form {
          margin: 20px;
          padding: 20px;
          border: none;
          border-radius: 5px;
          margin-left: -1%;
        }

        label {
          display: none;
        }

        input[type="date"] {  
          padding: 12px;
          margin-left: -1%;
          margin-right: 10px;
          box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2), 
                       -3px -3px 10px rgba(255, 255, 255, 0.5);
          background: linear-gradient(to bottom, #fff, #f1f1f1);
          border-radius: 10px;
          border: 1px solid #ccc;
          width: 35%;
    }

    button {
        padding: 12px;
        width: 8%;
        background-color: #008000;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #004D00;
    }

    a {
        text-decoration: none;
        color: white;
        background-color:#007BFF;
        padding: 12px;
        font-size: 13px;
        width: 15px;
        border: none;
        border-radius: 4px;
        margin-left: 10px;
    }

    a:hover {
        background-color:rgb(3, 88, 179);
    }
        
    </style>
</head>
<body>
<h2>Your Completed Appointment History</h2>
<form method="GET">
    <label for="filter_date">Filter by Appointment Date:</label>
    <input type="date" name="filter_date" id="filter_date" value="<?php echo htmlspecialchars($filter_date); ?>">
    <button type="submit">Search</button>
    <a href="user_history.php">Reset</a>
</form>
<table>
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Pansion Name</th>
            <th>Service</th>
            <th>Appointment Date and Time</th>
            <th>Done Photo</th>
            <th>Rating</th>
            <th>Feedback</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($appointments)): ?>
        <?php foreach ($appointments as $appointment): ?>
            <tr>
                <td><?= htmlspecialchars($appointment['client_name']) ?></td>
                <td><?= htmlspecialchars($appointment['pansion_name']) ?></td>
                <td><?= htmlspecialchars($appointment['service']) ?></td>
                <td><?= htmlspecialchars($appointment['appointment_datetime']) ?></td>
                <td>
                    <?php if ($appointment['proof_image']): ?>
                        <img src="<?= htmlspecialchars($appointment['proof_image']); ?>" alt="Proof Image" class="proof">
                    <?php else: ?>
                        No Proof Provided
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($appointment['rating']) ?> ⭐</td>
                <td><?= htmlspecialchars($appointment['feedback']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">No completed appointments found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
