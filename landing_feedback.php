<?php
include("database_connection.php");

// Fetch appointment data along with the client name and other details
$query = "
    SELECT u.complete_name, a.pansion_name, a.service, a.price, a.proof_picture, a.proof_image, a.rating, a.feedback
    FROM appointments a
    JOIN users u ON a.user_id = u.id
    WHERE a.rating IS NOT NULL AND a.feedback IS NOT NULL
    ORDER BY a.id DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Landing Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            height: 90vh;
            margin: 20px;
            background: 
             linear-gradient(rgba(31, 31, 31, 0.6), rgba(31, 31, 31, 0.6)), 
             url('image_bg.jpg') no-repeat center center;
            background-size: cover;
        }

        h2 {
            font-weight: bold;
           font-size: 2.5em;
           margin-top: 1%;
           margin-bottom: 20px;
           color: white;
           padding: 10px;
           text-shadow: 
              2px 2px 0 #008800, 
              4px 4px 0 #005500,
              px 6px 10px rgba(0,0,0,0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #005500;
            color: white;
            padding: 15px;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            color: green;
        }
    </style>
</head>
<body>
<h2>Client Feedback</h2>

<?php if (isset($message)): ?>
    <div class="message"><?= $message ?></div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Pansion Name</th>
            <th>Service</th>
            <th>Price</th>
            <th>Payment Proof</th>
            <th>Done Proof</th>
            <th>Rating</th>
            <th>Feedback</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['complete_name']) ?></td>
                    <td><?= htmlspecialchars($row['pansion_name']) ?></td>
                    <td><?= htmlspecialchars($row['service']) ?></td>
                    <td><?= htmlspecialchars($row['price']) ?> PHP</td>
                    <td>
                        <?php if ($row['proof_picture']): ?>
                            <img src="<?= htmlspecialchars($row['proof_picture']) ?>" alt="Proof Picture" width="100">
                        <?php else: ?>
                            No image
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['proof_image']): ?>
                            <img src="<?= htmlspecialchars($row['proof_image']) ?>" alt="Proof Image" width="100">
                        <?php else: ?>
                            No image
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['rating']) ?>⭐</td>
                    <td><?= htmlspecialchars($row['feedback']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No feedback found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

<?php
$conn->close();
?>
