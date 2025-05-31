<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            flex-direction: column;
        }


        .logo img {
            height: 60px;
            width: 60px;
            margin-right: 10px;
            border-radius: 50%;
            text-decoration: none; 
        }

        .menu {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        .navbar {
            height: 10%;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            /* 3D Box Effect */
            box-shadow: 
            0 5px 0 #000000,       /* Bottom hard shadow for 3D effect */
            0 10px 15px rgba(0, 0, 0, 0.7), /* Soft deep shadow */
            inset 0 5px 10px rgba(255, 255, 255, 0.1); /* Subtle inner highlight */
            /* Optional Gradient for 3D Effect */
            background: linear-gradient(145deg,rgb(56, 56, 56),rgb(54, 54, 54)); /* Dark gradient */
        }

        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .navbar .menu {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
            padding: 0;
        }

        .navbar .menu li {
            display: inline;
        }

        .navbar .menu a,
        .navbar button {
            width: 140px;
            height: 45px;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.3s, transform 0.2s;
            text-decoration: none;
            background: linear-gradient(145deg,rgb(56, 56, 56),rgb(54, 54, 54)); /* Dark gradient */
        }

        .navbar button {
            border: none;
        }
        
        .navbar button.active,
        .navbar .menu a:hover,
        .navbar button:hover {
            background-color: #00695c; /* fallback */
            background-image: linear-gradient(315deg, #00b894 30%, #00c853 70%);
            outline: none;
            box-shadow: none;
            border: none;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
            text-align: center;
            height: 520px;
        }

        .content iframe {
            width: 103.5%;
            height: 106.3%;
            border: none;
            margin-left: -2%;
            margin-top: -1%;

        }

        .default-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .default-content h1 {
            color: #007bff;
        }

.logout-btn {
    background-color: #8b0000;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
}

.logout-btn:hover {
    background-color: rgb(153, 7, 7);
}

/* Notification Button */
.notif-btn {
    background-color: transparent; /* No background */
    width: 10px;
    border: none;
    cursor: pointer;
    font-size: 20px;
    position: relative;
    margin-right: -5%; /* Space between buttons */
}

/* Notification Icon */
.notif-btn i {
    color: #007bff; /* Change icon color */
    font-size: 25px;
}

/* Notification Badge */
.notif-badge {
    position: absolute;
    top: 2px;
    margin-left: 20%;
    background-color: red;
    color: white;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 50%;
}

/* Hover Effect */
.notif-btn:hover  {
    color: darkred;
}

</style>
</head>
<body>
<div class="navbar">
    <div class="logo">
        <img src="image_logo.png">
        Booking System
    </div>
    <ul class="menu">
       <button onclick="loadPage('landing_home.php', this)"><i class="fas fa-home"></i> Home</button>     
       <button onclick="loadPage('landing_about.php', this)"><i class="fas fa-list"></i> About Us</button>
       <button onclick="loadPage('landing_service.php', this)"><i class="fas fa-history"></i>Service</button>
       <button onclick="loadPage('landing_feedback.php', this)"><i class="fas fa-history"></i>Feedback</button>
       <li><a href="login.php"><i class="fas fa-sign-out-alt"></i> Sign Up</a></li>
    </ul>
</div>

    <div class="content">
        <iframe id="contentFrame" class="content-frame" src="landing_home.php"></iframe>
    </div>

<script>
window.onload = function () {
    const frame = document.getElementById('contentFrame');
    
    // Ensure activePage is defined before using it
    if (typeof activePage !== "undefined" && activePage !== null) {
        frame.src = activePage;
    
        // Highlight the correct button as active
        document.querySelectorAll('.navbar button').forEach(button => {
            if (button.onclick.toString().includes(activePage)) {
                button.classList.add('active');
                button.focus(); // Automatically focus on the active button
            }
        });
    }
};

function loadPage(page, btn) {
    const frame = document.getElementById('contentFrame');
    frame.src = page;

    // Remove active class from all buttons
    document.querySelectorAll('.navbar button').forEach(button => {
        button.classList.remove('active');
    });

    // Add active class to clicked button
    btn.classList.add('active');
    btn.focus(); // Keep the focus on the active button
}

</script>
</body>
</html>