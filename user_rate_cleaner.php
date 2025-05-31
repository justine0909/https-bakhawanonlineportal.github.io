<?php
session_start();
include 'database_connection.php';

// Ensure the user is logged in and has an appointment
if (!isset($_SESSION['user_id']) || !isset($_GET['appointment_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$appointment_id = $_GET['appointment_id'];

// Fetch the appointment details to display information
$query = "SELECT * FROM appointments WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $appointment_id, $user_id);
$stmt->execute();
$appointment = $stmt->get_result()->fetch_assoc();

if (!$appointment) {
    echo "Appointment not found or you don't have access to rate this appointment.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];

    // Validate the rating (ensure it's between 1 and 5)
    if ($rating < 1 || $rating > 5) {
        echo "Invalid rating. Please select a rating between 1 and 5.";
    } else {
        // Update the database with the rating and feedback
        $update_query = "UPDATE appointments SET rating = ?, feedback = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("isi", $rating, $feedback, $appointment_id);
        if ($stmt->execute()) {
            $_SESSION['alert_message'] = "Thank you for your feedback!";
            header('Location: user_feedback.php');
            exit;
        } else {
            echo "Error updating rating and feedback.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rate Cleaner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
body {
    font-family: 'Poppins', Arial, sans-serif;
    min-height: 100vh;
    margin: 0;
    padding: 0;
}

.container {
    width: 100%;
    max-width: 450px;
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    animation: fadeIn 1s ease;
}

h2 {
    text-align: center;
    color: #003500; /* Match your sidebar green */
    margin-bottom: 40px;
    font-weight: 900;
    font-size: 35px;
    letter-spacing: 2px;
    text-transform: uppercase;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    font-weight: 600;
    font-size: 20px;
    color: #333;
    margin-bottom: 8px;
    text-align: left; /* ← change from center to left */
}

select, textarea, input {
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ccc;
    font-size: 16px;
    transition: border-color 0.3s;
    width: 100%;
    box-sizing: border-box;
}

select:focus, textarea:focus, input:focus {
    border-color:rgb(0, 230, 0);
    outline: none;
}


/* Optional small back button or link */
.back-link {
    display: block;
    text-align: center;
    margin-top: -10px;
    color: #003500;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s;
}

.back-link:hover {
    color: #006400;
}

/* Fade in animation */
@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(-20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.star-rating {
    display: flex;
    gap: 10px; /* space between stars */
    margin-bottom: 15px;
    margin-left: 12%;
}

.star-rating i {
    font-size: 45px; /* ← adjust this to make stars bigger or smaller */
    color: #ccc;
    cursor: pointer;
    transition: color 0.3s ease, transform 0.2s ease;
    margin-left: 2%;
}

.star-rating i:hover,
.star-rating i.selected {
    color: #FFD700; /* gold color for selected star */
    transform: scale(1.2);
}

.form-group {
    margin-bottom: 20px;
}

/* For the submit button */
button.btn {
    margin-left: 38%;
    background-color: #003500;
    padding: 15px;
    border-radius: 10px;
    border: none;
    color: white;
    cursor: pointer;
    transition: background 0.3s;
}

button.btn:hover {
    background-color: #006400;
}

    </style>
</head>
<body>

<div class="container">
    <h2>Rate Cleaner</h2>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="rating">Rating:</label>
            <div class="star-rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star" data-rating="<?= $i ?>" onclick="setRating(<?= $i ?>)"></i>
                <?php endfor; ?>
            </div>
            <input type="hidden" id="rating" name="rating" value="" required>
        </div>

        <div class="form-group">
            <label for="feedback">Feedback:</label>
            <textarea name="feedback" id="feedback" rows="4" placeholder="Provide feedback..." required></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn">Submit Feedback</button>
        </div>
    </form>
</div>

<script>
    function setRating(rating) {
        const stars = document.querySelectorAll('.star-rating i');
        document.getElementById('rating').value = rating;

        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('selected');
            } else {
                star.classList.remove('selected');
            }
        });
    }
</script>

</body>
</html>
