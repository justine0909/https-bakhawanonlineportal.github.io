<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("database_connection.php");

// Handle deletion of appointment
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $deleteQuery = "DELETE FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "Appointment deleted successfully!";
    } else {
        $message = "Error deleting appointment.";
    }
    $stmt->close();
}

// Handle search functionality
$searchQuery = "";
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $searchQuery = "AND u.complete_name LIKE '%" . $conn->real_escape_string($searchTerm) . "%'";
}

// Fetch appointment data along with the client name
$query = "
    SELECT u.complete_name, a.pansion_name, a.service, a.rating, a.feedback, a.id AS appointment_id
    FROM appointments a
    JOIN users u ON a.user_id = u.id
    WHERE a.rating IS NOT NULL AND a.feedback IS NOT NULL $searchQuery
    ORDER BY a.id DESC
";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Appointment History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
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
            margin-top: 20px;
        }

        .container { 
            width: 94.5%; 
            margin: 50px auto; 
            padding: 20px; 
            border-radius: 8px;
            margin-left: 2%;
            margin-top: 1%;
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
            background-color: #008631;
            color: white;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            color: green;
        }

        .delete-button {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-button:hover {
            text-decoration: underline;
        }

        .search-box {
           display: flex;
           justify-content: left;
           margin: 20px 0; 
        }

       .search-box input[type="text"] {
           padding: 10px;
           border: 1px solid #ccc;
           border-radius: 5px;
           width: 300px;
           font-size: 16px;
        }

       .search-box button {
           padding: 10px 15px;
           border: none;
           border-radius: 5px;
           background-color: #008631;
           color: white;
           cursor: pointer;
           font-size: 16px;
           margin-left: 10px;
        }

        .search-box button:hover {
           background-color: #1fd655; /* Darker red on hover */
        }
        
    </style>
</head>
<body>
<div class="container">
<h2>Appointment History</h2>
<form method="GET" action="" class="search-box">
    <input type="text" name="search" placeholder="Search by client name" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
    <button type="submit">Search</button>
</form>

<table>
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Pansion Name</th>
            <th>Service</th>
            <th>Rating</th>
            <th>Feedback</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['complete_name']) ?></td>
                    <td><?= htmlspecialchars($row['pansion_name']) ?></td>
                    <td><?= htmlspecialchars($row['service']) ?></td>
                    <td><?= htmlspecialchars($row['rating']) ?>⭐</td>
                    <td><?= htmlspecialchars($row['feedback']) ?></td>
                    <td>
                        <a href="?delete_id=<?= $row['appointment_id'] ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No appointment history found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</body>
</html>

<?php
$conn->close();
?>
