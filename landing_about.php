<?php
// landing_about.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Our System</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #e8f5e9, #ffffff);
            margin: 0;
            padding: 0;
            color: #2e7d32;
        }

        .main-container {
            max-width: 1200px;
            height: 50vh;
            margin:auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
            font-size: 2.0em;
            margin-top: 3%;
            margin-bottom: 20px;
            color: #00cc00;
            padding: 2px;
            text-shadow: 
              2px 2px 0 #008800, 
              4px 4px 0 #005500,
              px 6px 10px rgba(0,0,0,0.5);
        }

        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 60px;
        }

        .content-box {
            background: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.2);
            transition: transform 0.3s;
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

        .content-box:hover {
            transform: translateY(-5px);
        }

        .content-box p {
            font-size: 18px;
            line-height: 1.8;
            text-align: justify;
            color: black;
        }

        .extra-text {
            padding: 40px;
            width: 105.5%;
            height: 240px;
            background-color: #005500;
            margin-left: -6%;
            margin-top: -3%;
        }

        .extra-text p {
            font-size: 16px;
            text-align: justify;
            line-height: 1.8;
            padding: 1px;
            color: white;
        }

        .head1 {
            margin-top: -2%;
            margin-left: -6%;
            margin-bottom: 2%;
            padding: 10px;
            width: 110.5%;
            height: 170px;
            background: 
               linear-gradient(rgba(31, 31, 31, 0.6), rgba(31, 31, 31, 0.6)), /* transparent black overlay */
               url('image_bg.jpg') no-repeat center center;
            background-size: cover;
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            color:white;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>

<div class="main-container">
  <div class="head1">
    <div class="section-title">
      <h2>About Our System</h2>
    </div>
  </div>
    <div class="content-wrapper">
        <div class="content-box">
            <p>
                Transforming cemetery upkeep through innovation and care, our system introduces a seamless and accessible way to schedule services. 
                Designed to prioritize both efficiency and compassion, this platform simplifies communication between clients and service providers, 
                ensuring every request is handled with respect and promptness.
            </p>
        </div>
        <div class="content-box">
            <p>
                Our online portal enables real-time monitoring of cemetery maintenance activities, providing transparency and accountability. 
                Families can track progress, receive updates, and feel confident that the resting places of their loved ones are maintained 
                with the utmost dignity and care.
            </p>
        </div>
        <div class="content-box">
            <p>
                Community-centered support is at the heart of our mission. By fostering partnerships with local organizations and volunteers, 
                we ensure that maintenance efforts not only preserve the physical environment but also strengthen the spirit of togetherness 
                within Barangay Bakhawan.
            </p>
        </div>
        <div class="content-box">
            <p>
                Embracing sustainable practices, we incorporate eco-friendly solutions into every aspect of our maintenance services. 
                From reducing waste to promoting green landscaping, our commitment to sustainability honors both the community and the environment 
                for generations to come.
            </p>
        </div>
    </div>

    <div class="extra-text">
        <p>
            As we step into the future, we envision a modernized cemetery management experience that reflects the values of compassion, efficiency, and respect. 
            Our system is designed not just for convenience, but for creating a meaningful impact on how cemetery care is perceived and delivered.
        </p>

        <p>
            Join us in this journey towards innovation and tradition. Together, we can redefine cemetery services — ensuring that every resting place is treated 
            with honor and every family is supported with empathy. Let us build a lasting legacy for the community of Barangay Bakhawan and beyond.
        </p>

        <p>
            Through continuous improvement, feedback, and community engagement, we are committed to delivering a service that brings peace of mind to families 
            and preserves the heritage of our loved ones. Thank you for trusting us to care for what matters most.
        </p>
    </div>
</div>

</body>
</html>
