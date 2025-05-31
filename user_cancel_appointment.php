<?php
session_start();
include 'database_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if appointment ID is passed
if (isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    // Update the status of the appointment to 'Cancelled'
    $query = "UPDATE appointments SET status = 'Cancelled' WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $appointment_id, $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        // Successfully canceled, redirect back to appointments page
        header("Location: user_view_appointment.php");
        exit();
    } else {
        // Error occurred
        echo "Error canceling appointment.";
    }

    $stmt->close();
}

$conn->close();
?>
