<?php
include 'database_connection.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$query = "SELECT COUNT(*) AS pending_count FROM appointments WHERE status = 'Pending'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$pending_count = $row['pending_count'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            background: #f4f4f4;
        }

        .sidebar {
            width: 270px;
            height: 100vh;
            background-color: #003500;
            color: white;
            padding: 20px;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            margin-top: -2%;
            font-size: 30px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li button {
            width: 115%;
            background: none;
            border: none;
            color: white;
            text-align: left;
            padding: 18px;
            margin-left: -8%;
            margin-top: -5%;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 20px;
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
            display: block;
            width: 115.5%;
            background:  #003500;
            color: white;
            text-align: center;
            padding: 18px;
            cursor: pointer;
            margin-top: -4%;
            margin-left: -8%;
            font-size: 20px;
            border: none;
            text-align: left;
        }

        .content {
            padding: 5px;
            width: 305%;
            height: 640px;
            max-width: 1200px;
            margin: 0 auto;
            overflow: auto;
            margin-left: 20%;
        }

        .content iframe {
            width: 100%;
            height: 656px;
            border: none;
            margin-top: -20px;
        }

        /* Notification Badge */
        .notif-badge {
            background: red;
            color: white;
            padding: 3px 8px;
            border-radius: 50%;
            font-size: 14px;
            position: absolute;
            right: 10px;
            margin-right: 7%;
        }

        /* Blinking Animation */
       .blink {
           animation: blinkAnimation 1s infinite;
        }

        @keyframes blinkAnimation {
           0% { opacity: 1; }
           50% { opacity: 0; }
           100% { opacity: 1; }
        }

        .logo-container img {
            width: 100%;
            height: 55%;
            display: block;
            margin: 0 auto 20px;
            border-radius: 50%;
        }

    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo-container">
            <img src="image_logo.png" alt="Logo">
        </div>
        <h2>Admin Dashboard</h2>
        <ul>
            <li><button onclick="loadPage('admin_dashboard.php', this)">Dashboard</button></li>
            <li>
               <button onclick="loadPage('admin_manage_appointment.php', this)">
                   Manage Appointments 
                  <?php if ($pending_count > 0): ?>
                  <span id="notifBadge" class="notif-badge blink"><?php echo $pending_count; ?></span>
                  <?php endif; ?>
               </button>
            </li>
            <li><button onclick="loadPage('admin_history.php', this)">Admin History</button></li>
            <li><button onclick="loadPage('admin_manage_user.php', this)">Manage Users</button></li>
        </ul>
        <button class="logout-btn" onclick="logout()">Logout</button>
    </div>

    <div class="content">
        <iframe id="contentFrame" class="content-frame" src="admin_dashboard.php"></iframe>
    </div>

    <script>
        window.onload = function () {
            const frame = document.getElementById('contentFrame');
            frame.src = activePage;

            // Highlight the correct button as active
            document.querySelectorAll('.sidebar button').forEach(button => {
                if (button.onclick.toString().includes(activePage)) {
                    button.classList.add('active');
                }
            });
        };

        function loadPage(page, btn) {
            const frame = document.getElementById('contentFrame');
            frame.src = page;
           

            // Remove active class from all buttons
            document.querySelectorAll('.sidebar button').forEach(button => {
                button.classList.remove('active');
            });

            // Add active class to clicked button
            btn.classList.add('active');
        }

        function logout() {
            if (confirm("Are you sure you want to logout?")) {
                window.location.href = "landing.php";
            }
        }

        function checkNewBookings() {
            fetch('getNewBookings.php')
                .then(response => response.json())
                .then(data => {
                    const notifBadge = document.getElementById('notifBadge');
                    if (data.newBookings > 0) {
                        notifBadge.textContent = data.newBookings;
                        notifBadge.style.display = 'inline-block';
                    } else {
                        notifBadge.style.display = 'none';
                    }
                })
                .catch(error => console.error('Error fetching new bookings:', error));
        }

        setInterval(checkNewBookings, 1000);
        checkNewBookings();

        setInterval(function() {
    fetch('check_pending.php')
    .then(response => response.json())
    .then(data => {
        const badge = document.getElementById('notifBadge');
        if (data.pending > 0) {
            badge.textContent = data.pending;
            badge.classList.add('blink');
        } else {
            badge.textContent = '';
            badge.classList.remove('blink');
        }
    });
}, 1000); // Every 5 seconds
    </script>
</body>
</html>
