<?php
session_start();
include 'database_connection.php';

// Check if form is submitted to assign cleaner
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the appointment and cleaner IDs from the form
    $appointment_id = $_POST['appointment_id'];
    $cleaner_id = $_POST['cleaner_id'];

    // Validate that cleaner ID is provided
    if (empty($cleaner_id)) {
        $_SESSION['message'] = "Please select a cleaner.";
        header("Location: admin_manage_appointment.php");
        exit();
    }

    // Update the appointment to assign the selected cleaner
    $query = "UPDATE appointments SET cleaner_id = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $cleaner_id, $appointment_id);

    // Execute the query
    if ($stmt->execute()) {
        $_SESSION['message'] = "Cleaner assigned successfully!";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    // Redirect back to the appointment management page
    header("Location: admin_manage_appointment.php");
    exit();
}

$conn->close();
?>
