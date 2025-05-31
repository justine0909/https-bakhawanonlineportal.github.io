<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Our Cleaning Appointment System</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            position: relative;
       }

       .background-blur {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: 
             linear-gradient(rgba(31, 31, 31, 0.6), rgba(31, 31, 31, 0.6)), /* transparent black overlay */
             url('image_bg.jpg') no-repeat center center;
          background-size: cover;
          z-index: -1;
        }

        .content {
          z-index: 1;
          text-align: center;
          color: white;
          background-color: #005500;
          width: 60%;
          padding: 20px;
          margin-top: 1%;
          margin-left: 19%;
          position: relative;
          border-radius: 8px; /* Optional, to make rounded corners */
          box-shadow: 0 0 15px rgba(0, 128, 0, 0.5); /* Initial light border */
          animation: glowAnimation 1.5s ease-in-out infinite alternate; /* Apply the glowing animation */
        }

        @keyframes glowAnimation {
        0% {
         box-shadow: 0 0 10px rgba(0, 255, 0, 0.5); /* Light green glow */
        }
        50% {
         box-shadow: 0 0 20px rgba(0, 255, 0, 1); /* Stronger green glow */
        }
        100% {
         box-shadow: 0 0 10px rgba(0, 255, 0, 0.5); /* Fade back to lighter glow */
        }
        }

        .hero-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }
        h1 {
           font-size: 3.5em;
           margin-top: 1%;
           margin-bottom: 20px;
           color: #00cc00;
           padding: 10px;
           text-shadow: 
              2px 2px 0 #008800, 
              4px 4px 0 #005500,
              px 6px 10px rgba(0,0,0,0.5);
        }

        p {
            font-size: 1.2em;
            line-height: 1.5;
            padding: 1px;

        }

        .cta-button {
            padding: 15px 30px;
            background-color: #28a745;
            color: white;
            border: none;
            font-size: 1.2em;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .cta-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="hero-section">
 <div class="background-blur">
 <h1>Welcome to Our Cleaning Appointment System</h1>
    <div class="content">
         <p>
            Tired of complicated booking processes and messy schedules? We've got you covered! 
            Our Cleaning Appointment System is designed to make managing your cleaning needs simple, fast, and stress-free. 
            <br><br>
            With just a few clicks, you can easily book professional cleaners, manage your appointments, and track your service history anytime, anywhere. 
            Whether you’re at home or at work, scheduling a cleaning session has never been this convenient.
            <br><br>
            Embrace the future of cleaning services. Simplify your life. Save your time. Experience cleaning, the smarter way.
            <br><br>
            <strong>Book your first appointment now and feel the difference!</strong>
          </p>
    </div>
 </div>
</div>

</body>

</html>
