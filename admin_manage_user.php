<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("database_connection.php");

// Initialize message variable
$message = "";

// Handle deletion for users
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $deleteQuery = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "User deleted successfully!";
        echo "<script>alert('User deleted successfully!');</script>";
    } else {
        $message = "Error deleting user.";
        echo "<script>alert('Error deleting user.');</script>";
    }
    $stmt->close();
}

// Handle deletion for cleaners
if (isset($_GET['delete_cleaner_id'])) {
    $delete_cleaner_id = $_GET['delete_cleaner_id'];

    $deleteCleanerQuery = "DELETE FROM cleaners WHERE id = ?";
    $stmt = $conn->prepare($deleteCleanerQuery);
    $stmt->bind_param("i", $delete_cleaner_id);
    if ($stmt->execute()) {
        $message = "Cleaner deleted successfully!";
        echo "<script>alert('Cleaner deleted successfully!');</script>";
    } else {
        $message = "Error deleting cleaner.";
        echo "<script>alert('Error deleting cleaner.');</script>";
    }
    $stmt->close();
}

// Handle adding a new cleaner
if (isset($_POST['add_cleaner'])) {
    $cleaner_name = trim($_POST['cleaner_name']);

    if (!empty($cleaner_name)) {
        $insertQuery = "INSERT INTO cleaners (name) VALUES (?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("s", $cleaner_name);

        if ($stmt->execute()) {
            $message = "Cleaner added successfully!";
            echo "<script>alert('Cleaner added successfully!');</script>";
            // Refresh page after adding to show the new cleaner
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            $message = "Error adding cleaner.";
            echo "<script>alert('Error adding cleaner.');</script>";
        }
        $stmt->close();
    } else {
        $message = "Please enter a name.";
        echo "<script>alert('Please enter a name.');</script>";
    }
}

// Fetch users (only users, not admin accounts)
$userQuery = "SELECT id, complete_name, email, contact_number FROM users WHERE role = 'user'";
$userResult = $conn->query($userQuery);

// Fetch cleaners
$cleanerQuery = "SELECT id, name FROM cleaners";
$cleanerResult = $conn->query($cleanerQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Users and Cleaners</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background: #f4f4f4;
        }

        h2 {
            text-align: left;
            color: black;
            margin-bottom: 10px;
            font-weight: 900;
            font-size: 25px;
            text-transform: uppercase;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            padding-bottom: 15px;
            margin-top: 20px;
        }

        .container { 
            height: 83vh;
            width: 96%; 
            margin: 50px auto; 
            padding: 20px; 
            margin-left: 2%;
            margin-top: 1%;
        }

        .scroll {
           padding: 20px;
           width: 100%;
           overflow-y: auto;
           height: 170px;
           margin-left: -2%;
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

        a.delete-button {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        a.delete-button:hover {
            text-decoration: underline;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            color: green;
        }

        .form-container {
            margin-bottom: 20px;
        }

.form-container input[type="text"] {
    padding: 12px;
    width: 300px;
    border: 1px solid #ccc;
    border-radius: 10px;
}

.form-container button {
    padding: 12px;
    background-color: #003500;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.form-container button:hover {
    background-color: #008631;
}
    </style>
</head>
<body>
<div class="container">
<h2>Manage Users</h2>
<div class="scroll">
  <table>
    <thead>
        <tr>
            <th>Complete Name</th>
            <th>Email</th>
            <th>Contact Number</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($userResult && $userResult->num_rows > 0): ?>
            <?php while ($row = $userResult->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['complete_name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['contact_number']) ?></td>
                    <td>
                        <a href="?delete_id=<?= $row['id'] ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">No users found.</td></tr>
        <?php endif; ?>
    </tbody>
  </table>
</div>

<h2>Manage Cleaners</h2>
<div class="scroll">
<div class="form-container">
    <form action="" method="post">
        <input type="text" name="cleaner_name" placeholder="Enter cleaner name" required>
        <button type="submit" name="add_cleaner" onclick="alert('Cleaner added successfully!')">Add Cleaner</button>
    </form>
</div>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($cleanerResult && $cleanerResult->num_rows > 0): ?>
            <?php while ($row = $cleanerResult->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>
                        <a href="?delete_cleaner_id=<?= $row['id'] ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this cleaner?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="3">No cleaners found.</td></tr>
        <?php endif; ?>
    </tbody>
 </table>
</div>
</div>
</body>
</html>

<?php
$conn->close();
?>
