<?php
session_start();
include 'database_connection.php';

// Get values
if (isset($_POST['appointment_id']) && isset($_POST['action'])) {
    $appointment_id = $_POST['appointment_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $new_status = 'Approved';
    } elseif ($action == 'cancel') {
        $new_status = 'Cancelled';
    } else {
        die("Invalid action.");
    }

    $query = "UPDATE appointments SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $new_status, $appointment_id);

    if ($stmt->execute()) {
        header("Location: admin_manage_appointment.php?message=Status Updated");
        exit();
    } else {
        echo "Failed to update status.";
    }
}
?>
