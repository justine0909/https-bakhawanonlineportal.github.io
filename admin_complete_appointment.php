<?php
session_start();
include 'database_connection.php';

// Check if the appointment ID is provided
if (isset($_POST['appointment_id'])) {
    $appointment_id = $_POST['appointment_id'];

    // Update the appointment status to 'Completed'
    $update_query = "UPDATE appointments SET status = 'Complete' WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $appointment_id);

    if ($stmt->execute()) {
        $_SESSION['alert_message'] = "Appointment marked as completed successfully.";
    } else {
        $_SESSION['alert_message'] = "Failed to mark appointment as completed.";
    }

    // Redirect back to the admin dashboard
    header("Location: admin_dashboard.php");
    exit;
} else {
    // If no appointment ID is provided, redirect to the admin dashboard
    header("Location: admin_dashboard.php");
    exit;
}
