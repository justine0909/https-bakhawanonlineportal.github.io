<?php
session_start();
include 'database_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch appointments joined with cleaners, excluding ones that already have a rating or feedback
$query = "
    SELECT c.name, a.proof_image, a.status, a.rating, a.id AS appointment_id
    FROM appointments AS a
    INNER JOIN cleaners AS c ON a.cleaner_id = c.id
    WHERE a.user_id = ? AND a.status = 'Complete' AND (a.rating IS NULL OR a.feedback IS NULL)
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
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
    <title>User Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            margin: 20px;
        }

        h2 {
           font-weight: 900;
           font-size: 50px;
           padding: 15px;
           text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #003500;
            color: white;
            padding: 15px;
        }

        tr:nth-child(even) {
            background-color:rgb(197, 248, 185);
        }

        img.proof {
            width: 100px;
            height: auto;
            border-radius: 8px;
        }


.btn {
    display: inline-block;
    padding: 8px 16px;
    background-color: maroon;
    color: white;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn:hover {
    background-color:rgb(117, 6, 6);
    transform: scale(1.05);
}

.view-proof {
    display: inline-block;
    padding: 8px 16px;
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


.no-proof {
    color: red;
    font-style: italic;
    font-size: 14px;
}


    </style>
</head>
<body>
<h2>Give Feedback</h2>
<table>
    <thead>
        <tr>
            <th>Cleaner Name</th>
            <th>Proof Done</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($appointments)): ?>
        <?php foreach ($appointments as $appointment): ?>
            <tr>
                <td><?= htmlspecialchars($appointment['name']) ?></td>
                <td>
                    <?php if ($appointment['proof_image']): ?>
                      <a href="<?= htmlspecialchars($appointment['proof_image']); ?>" target="_blank" class="view-proof">View Image</a>
                   <?php else: ?>
                      <span class="no-proof">No Proof Provided</span>
                   <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($appointment['status']) ?></td>
                <td>
                     <?php if (empty($appointment['rating']) && empty($appointment['feedback'])): ?>
                       <a href="user_rate_cleaner.php?appointment_id=<?= htmlspecialchars($appointment['appointment_id']) ?>" class="btn">Rate</a>
                     <?php else: ?>
                     <span class="rated">Rated: <?= htmlspecialchars($appointment['rating']) ?> ⭐</span>
                  <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="4">No completed appointments available for feedback.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
