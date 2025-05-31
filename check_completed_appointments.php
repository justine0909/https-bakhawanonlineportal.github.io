<?php
session_start();
include 'database_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

// Query to fetch the number of completed appointments
$query = "SELECT COUNT(*) AS completed_count FROM appointments WHERE user_id = ? AND status = 'Complete'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo $row['completed_count']; // Output the count of completed appointments

$stmt->close();
$conn->close();
?>
