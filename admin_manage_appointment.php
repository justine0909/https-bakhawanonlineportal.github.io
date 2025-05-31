<?php
session_start();
include 'database_connection.php';

// Fetch appointments with the client name and cleaner name using JOIN
$query = "SELECT a.*, u.complete_name as client_name, c.name as cleaner_name
          FROM appointments a
          JOIN users u ON a.user_id = u.id
          LEFT JOIN cleaners c ON a.cleaner_id = c.id
          WHERE a.status = 'Pending'";  // Fetch pending appointments only
$result = $conn->query($query);

$appointments = [];
if ($result && $result->num_rows > 0) {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch available cleaners for the dropdown (replace 'cleaners' with your actual table name)
$cleaners_query = "SELECT * FROM cleaners";
$cleaners_result = $conn->query($cleaners_query);
$cleaners = [];
if ($cleaners_result && $cleaners_result->num_rows > 0) {
    $cleaners = $cleaners_result->fetch_all(MYSQLI_ASSOC);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - View Appointments</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { 
            font-family: Arial; 
        }

        h2 {
            text-align: center;
            color: #003500;
            margin-bottom: 10px;
            font-weight: 900;
            font-size: 40px;
            text-transform: uppercase;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            padding-bottom: 15px;
            border-bottom: 2px solid #ccc;
            margin-top: 40px;
        }

        .container { 
            width: 95.5%; 
            margin: 50px auto; 
            padding: 20px; 
            border-radius: 8px; 
            margin-top: -1%;
            margin-left: 1%;
        }

        table { 
            width: 102%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }

        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: center; 
        }

        th { 
            background: #008631; 
            color: white; 
        }

        td { 
            background: #fafafa; 
            font-size: 14px;
        }

        .btn { 
            padding: 8px; 
            border-radius: 2px;
            border: none; 
            cursor: pointer; 
            color: white;
            background-color:rgb(212, 145, 0);
            margin-top: 3%;
            width: 70%;
        }

        .approve { 
            background-color: green; 
            color: white; 
            font-size: 13px;
            width: 70%;
        }

        .cancel { 
            margin-top: 2%;
            width: 70%;
            background-color: red; 
            color: white;
            font-size: 13px; 
        }

        .view-proof {
            display: inline-block;
            border-radius: 2px;
            padding: 8px;
            background-color: #1e90ff;
            width: 70%;
            color: white;
            text-decoration: none;
            font-size: 12px;
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

<div class="container">
    <h2>Admin - Manage Appointments</h2>
    <table>
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Pansion</th>
                <th>Service</th>
                <th>Payment Method</th>
                <th>Payment Proof</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Cleaner</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?= htmlspecialchars($appointment['client_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['pansion_name']) ?></td>
                    <td><?= htmlspecialchars($appointment['service']) ?></td>
                    <td><?= htmlspecialchars($appointment['payment_method']) ?></td>
                    <td>
                        <?php if ($appointment['proof_picture']): ?>
                            <a href="<?= htmlspecialchars($appointment['proof_picture']) ?>" target="_blank" class="view-proof">View Proof</a>
                        <?php else: ?>
                            No Proof Provided
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($appointment['appointment_datetime']) ?></td>
                    <td><?= htmlspecialchars($appointment['status']) ?></td>
                    <td>
                        <!-- Dropdown for assigning cleaner -->
                        <form method="post" action="admin_assign.php" style="display:inline;" onsubmit="return confirmAssign();">
                            <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                            <select name="cleaner_id" class="form-control">
                                <option value="">Select Cleaner</option>
                                <?php foreach ($cleaners as $cleaner): ?>
                                    <option value="<?= $cleaner['id'] ?>" <?= $appointment['cleaner_id'] == $cleaner['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cleaner['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn">Assign</button>
                        </form>
                    </td>
                    <td>
                        <?php if ($appointment['status'] == 'Pending'): ?>
                            <form method="post" action="admin_update_status.php" style="display:inline;" onsubmit="return confirmApprove();">
                                <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="btn approve">Approve</button>
                            </form>
                            <form method="post" action="admin_update_status.php" style="display:inline;" onsubmit="return confirmCancel();">
                                <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                <input type="hidden" name="action" value="cancel">
                                <button type="submit" class="btn cancel">Cancel</button>
                            </form>
                        <?php else: ?>
                            No Action Available
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function confirmAssign() {
        var cleanerSelect = document.querySelector('select[name="cleaner_id"]');
        if (cleanerSelect.value === "") {
            alert("Please select a cleaner before assigning.");
            return false; // Prevent form submission
        }
        alert("Cleaner assigned successfully!");
        return true; // Allow form submission
    }

    function confirmApprove() {
        var cleanerSelect = document.querySelector('select[name="cleaner_id"]');
        if (cleanerSelect.value === "") {
            alert("Please assign a cleaner before approving the appointment.");
            return false; // Prevent form submission
        }
        var confirmation = confirm("Are you sure you want to approve this appointment?");
        if (confirmation) {
            alert("Appointment approved!");
            return true; // Allow form submission
        }
        return false; // Prevent form submission
    }

    function confirmCancel() {
        var confirmation = confirm("Are you sure you want to cancel this appointment?");
        if (confirmation) {
            alert("Appointment canceled!");
            return true; // Allow form submission
        }
        return false; // Prevent form submission
    }
</script>

</body>
</html>
