<?php
session_start();
include 'database_connection.php';

// Fetch only approved appointments
$query = "SELECT a.*, u.complete_name as client_name, c.name as cleaner_name, a.pansion_name, a.appointment_datetime
          FROM appointments a
          JOIN users u ON a.user_id = u.id
          LEFT JOIN cleaners c ON a.cleaner_id = c.id
          WHERE a.status = 'Approved'";  // Only Approved appointments
$result = $conn->query($query);

$appointments = [];
if ($result && $result->num_rows > 0) {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
}

// Check if an alert message is set
$alert_message = '';
if (isset($_SESSION['alert_message'])) {
    $alert_message = $_SESSION['alert_message'];
    unset($_SESSION['alert_message']);
}

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['proof_image'])) {
    $appointment_id = $_POST['appointment_id'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["proof_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a valid image (check if it's an image is removed)
    // No need to check for image type anymore

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["proof_image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // If everything is OK, upload the file
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["proof_image"]["tmp_name"], $target_file)) {
            // Update database with the file path
            $update_query = "UPDATE appointments SET proof_image = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("si", $target_file, $appointment_id);
            $stmt->execute();
            $_SESSION['alert_message'] = "Proof uploaded successfully.";
            header("Location: admin_dashboard.php");
            exit;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch total appointments by status
$complete_appointments = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE status = 'Complete'")->fetch_assoc()['total'];
$approved_appointments = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE status = 'Approved'")->fetch_assoc()['total'];
$pending_appointments = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE status = 'Pending'")->fetch_assoc()['total'];
$canceled_appointments = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE status = 'Cancelled'")->fetch_assoc()['total'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Overview</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { 
            font-family: Arial;
        }

        .container { 
            width: 94.5%; 
            margin: 50px auto; 
            padding: 20px; 
            border-radius: 8px;
            margin-left: 2%;
            margin-top: 1%;
        }

        h1 {
            text-align: center;
            color: black;
            margin-bottom: 10px;
            font-weight: 900;
            font-size: 40px;
            text-transform: uppercase;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            padding-bottom: 15px;
            border-bottom: 2px solid #ccc;
            margin-top: 20px;
        }

        h2 {
            text-align: left;
            color: black;
            font-weight: 900;
            font-size: 20px;
            text-transform: uppercase;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: -1%; 
        }

        th, td { 
            border: 1px solid #ddd; 
            padding: 13px; 
            text-align: center; 
        }

        th { 
            background: #003500; 
            color: white; 
        }

        td { 
            background: #fafafa; 
        }

        .btn { 
            padding: 8px 12px; 
            border: none; 
            border-radius: 2px; 
            cursor: pointer; 
        }

        .complete { 
            background-color: blue; 
            color: white; 
        }

        .approve { 
            background-color: green; 
            color: white; 
        }

        .cancel { 
            background-color: red; 
            color: white; 
        }

        .upload-btn { 
            margin-top: 1%;
            background-color: rgb(212, 145, 0); 
            color: white; 
        }

        .img-preview { 
            width: 100px; 
            height: 100px; 
            object-fit: cover; 
        }

        .dashboard { 
            display: flex; 
            justify-content: space-between; 
            margin-top: 20px; 
        }

        .card { 
            width: 20%; 
            height: 100px; 
            font-size: 14px;
            background: #83f38f; 
            color: black; 
            padding: 20px; 
            border-radius: 10px; 
            text-align: center; 
        }

        .card h3 { 
            margin: 0; 
            font-size: 18px; 
        }

        .card p { 
            font-size: 24px; 
            font-weight: bold; 
        }
    </style>
</head>
<body>

<div class="container">
<h1>Admin Dashboard</h1>
<div class="dashboard">
            <div class="card">
                <h3>Complete</h3>
                <p><?= $complete_appointments ?></p>
            </div>
            <div class="card" style="background: #5ced73;">
                <h3>Approved</h3>
                <p><?= $approved_appointments ?></p>
            </div>
            <div class="card" style="background: #39e75f;">
                <h3>Pending</h3>
                <p><?= $pending_appointments ?></p>
            </div>
            <div class="card" style="background: #1fd655;">
                <h3>Canceled</h3>
                <p><?= $canceled_appointments ?></p>
            </div>
   </div>

    <h2>Appointment Overview</h2>
    <?php if ($alert_message): ?>
        <script>
            alert("<?= $alert_message ?>");
        </script>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Cleaner Name</th>
                <th>Pansion</th>
                <th>Appointment Date & Time</th>
                <th>Status</th>
                <th>Proof Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?= htmlspecialchars($appointment['client_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['cleaner_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['pansion_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['appointment_datetime']) ?></td>
                    <td><?= htmlspecialchars($appointment['status']) ?></td>
                    <td>
                        <?php if ($appointment['proof_image']): ?>
                            <img src="<?= $appointment['proof_image'] ?>" class="img-preview" alt="Proof Image">
                        <?php else: ?>
                            <form method="post" enctype="multipart/form-data" action="">
                                <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                <input type="file" name="proof_image" required>
                                <button type="submit" class="btn upload-btn">Upload Proof</button>
                            </form>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($appointment['status'] == 'Approved'): ?>
                            <!-- Complete Button (Only visible for Approved appointments) -->
                            <form method="post" action="admin_complete_appointment.php" style="display:inline;">
                                <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                <button type="submit" class="btn complete">Complete</button>
                            </form>
                        <?php else: ?>
                            No action available
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
