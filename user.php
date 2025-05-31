<?php
session_start();
include 'database_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to fetch completed appointments and check if the user has rated
$query = "
    SELECT COUNT(*) AS completed_count 
    FROM appointments 
    WHERE user_id = ? 
    AND status = 'Complete' 
    AND (rating IS NULL OR rating = '')
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$completed_count = $row['completed_count']; // This will hold the count of completed appointments without feedback

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>User Panel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            background-color: #f4f4f4;
        }

        .sidebar {
            width: 300px;
            background: #003500;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100%;
        }

        /* Sidebar menu items */
        .sidebar ul {
           list-style-type: none;
           padding: 0;
        }

       .sidebar ul li {
          margin: 15px 0;
          text-align: left;
          margin-bottom: -1%;
        }

        .sidebar h2 {
            font-size: 30px;
            text-align: center;
            margin-bottom: 15px;
            margin-top: -2%;
        }
        
        .sidebar button {
            display: block;
            width: 116%;
            background: none;
            border: none;
            color: white;
            padding: 20px;
            text-align: left;
            margin-left: -8%;
            margin-top: -5.7%;
            cursor: pointer;
            font-size: 20px;
            transition: background 0.3s ease;
        }


        .sidebar button:hover,
        .sidebar ul li button:hover,
        .sidebar button.active {
            background-color: #00695c; /* fallback */
            background-image: linear-gradient(315deg, #00b894 30%, #00c853 70%);
            outline: none;
            box-shadow: none;
            border: none;
        }

        .logout-btn {
            position: absolute;
            left: 1px;
            margin-top: -4%;
            text-decoration: none;
            display: block;
            width: 100%;
            background: none;
            border: none;
            color: white;
            padding: 18px;
            text-align: left;
            cursor: pointer;
            font-size: 20px;
            margin-bottom: 10px;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #00695c; /* fallback */
            background-image: linear-gradient(315deg, #00b894 30%, #00c853 70%);
            outline: none;
            box-shadow: none;
            border: none;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
            height: 100vh;
            position: relative;
        }

        /* Background Image for Home */
        .home-background {
            width: 97%;
            height: 100%;
            background: 
             linear-gradient(rgba(31, 31, 31, 0.6), rgba(31, 31, 31, 0.6)), /* transparent black overlay */
             url('image_bg.jpg') no-repeat center center;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            position: absolute;
            top: 0;
            left: 3%;
        }
        .home-background h2 {
            color: black;               
            font-weight: bold;
            font-size: 50px;
            text-align: center;
            padding: 15px;
            letter-spacing: 3px;
            font-family: 'Georgia', serif;
            margin-top: 20px;
            margin-bottom: 20px;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2), 
                -3px -3px 10px rgba(255, 255, 255, 0.5); 
            background: linear-gradient(to bottom, #fff, #f1f1f1);
            transition: 0.3s ease-in-out;
            outline: none;
        }


        /* Iframe Styling */
        .content-frame {
            width: 100%;
            height: 105%;
            border: none;
            margin-top: -2%;
            margin-left: 1%;
        }
        
        .logo {
           display: block;
           width: 200px;
           height: auto;
           margin: 0 auto 15px;
           border-radius: 50%;
        }


.notif-icon {
    background-color: transparent; 
    width: 20%;
    border: none;
    cursor: pointer;
    font-size: 20px;
    position: relative;
    margin-top: -6%;
    margin-left: -6%;
}

.notif-btn i {
    color: blue;
    font-size: 25px;
    margin-top: -1%;
}

.notif-badge {
    position: absolute;
    top: 2px;
    margin-left: 15px;
    background-color: red;
    color: white;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 50%;
}

.notif-btn:hover  {
    color: darkred;
}
    </style>
</head>
<body>

<div class="sidebar">
    <img src="image_logo.png" alt="Company Logo" class="logo">
    <h2>Hi, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<!-- Notification Icon -->
<div class="notif-icon">
    <button class="notif-btn" onclick="loadPage('user_feedback.php', this)">
        <i class="fas fa-bell"></i>
        <?php if ($completed_count > 0): ?>
            <span class="notif-badge"><?php echo $completed_count; ?></span>
        <?php endif; ?>
    </button>
</div>
    <ul>
       <li><button onclick="showHomePage(this)">Home</button></li>
       <li><button onclick="loadPage('user_book_appointment.php', this)">Book Appointment</button></li>
       <li><button onclick="loadPage('user_view_appointment.php', this)">View Appointment</button></li>
       <li><button onclick="loadPage('user_history.php', this)">History</button></li>
       <li><a href="logout.php" class="logout-btn">Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <div id="homePage" class="home-background">
        <h2>IMPROVING CEMENTERY MAINTENANCE ONLINE PORTAL FOR SUSTAINABLE CARE IN BARANGAY BAKHAWAN</h2>
    </div>
    <iframe id="contentFrame" class="content-frame"></iframe>
</div>

<script>
function loadPage(page, btn) {
    document.getElementById('contentFrame').src = page;
    document.getElementById('contentFrame').style.display = "block"; // Show iframe
    document.getElementById('homePage').style.display = "none"; // Hide background

    document.querySelectorAll('.sidebar button').forEach(button => {
        button.classList.remove('active');
    });

    btn.classList.add('active');
}

function showHomePage(btn) {
    document.getElementById('contentFrame').style.display = "none"; // Hide iframe
    document.getElementById('homePage').style.display = "flex"; // Show home page

    document.querySelectorAll('.sidebar button').forEach(button => {
        button.classList.remove('active');
    });

    btn.classList.add('active');
}

window.onload = function () {
    showHomePage(document.querySelector('.sidebar button')); // Set Home as default
};

// This JavaScript will check for completed appointments every 30 seconds
setInterval(function() {
    // AJAX request to fetch the number of completed appointments
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'check_completed_appointments.php', true); // Create a new PHP file 'check_completed_appointments.php' that returns the count
    xhr.onload = function() {
        if (xhr.status === 200) {
            var completedCount = xhr.responseText; // This will be the number of completed appointments
            var notificationBadge = document.querySelector('.notification-badge');
            
            // If there are completed appointments, display the badge
            if (completedCount > 0) {
                notificationBadge.textContent = completedCount;
                notificationBadge.style.display = 'inline-block'; // Show the badge
            } else {
                notificationBadge.style.display = 'none'; // Hide the badge if there are no completed appointments
            }
        }
    };
    xhr.send();
}, 30000); // 30 seconds interval

</script>

</body>
</html>
