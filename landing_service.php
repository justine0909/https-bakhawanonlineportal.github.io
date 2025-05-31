<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - Cemetery Maintenance System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        section {
            padding: 40px 20px;
        }

        .head1 {
            margin-top: -3%;
            margin-left: -1%;
            padding: 10px;
            width: 101%;
            height: 100%;
            background: 
               linear-gradient(rgba(0, 66, 0, 0.6), rgba(0, 66, 0, 0.6)), /* transparent black overlay */
               url('image_bg.jpg') no-repeat center center;
            background-size: cover;
        }

        .section-title {
            text-align: center;
            font-size: 2rem;
            color:white;
            margin-bottom: 20px;
        }

        .service-list {
            max-width: 1400px;
            margin: 0 auto;
            padding: 15px;
            text-align: center;
        }

        .service-card {
            display: inline-block;
            width: 40%;
            margin: 15px;
            padding: 25px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            transition: transform 0.3s ease;
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

        .service-card:hover {
            transform: scale(1.05);
        }

        .service-card h3 {
            color: #5E0000;
        }

        h2 {
            font-size: 2.0em;
            margin-top: 1%;
            margin-bottom: 20px;
            color: #00cc00;
            padding: 2px;
            text-shadow: 
              2px 2px 0 #008800, 
              4px 4px 0 #005500,
              px 6px 10px rgba(0,0,0,0.5);
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .goal-section {
            background-color: #fff;
            padding: 20px;
            margin-top: -2%;
            text-align: center;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .goal-section h3 {
            color: #5E0000;
        }

        .goal-section p {
            font-size: 15px;
            color: black;
        }
    </style>
</head>
<body>

<section id="services">
 <div class="head1">
    <div class="section-title">
        <h2>Our Services</h2>
        <p>We offer a variety of services to ensure that the graves of your loved ones are properly maintained, even if you cannot visit them in person.</p>
    </div>
 </div>

    <div class="service-list">
        <div class="service-card">
            <h3>Simple Package</h3>
            <p>The Simple Package provides basic cemetery maintenance, including cleaning, removing debris, and tidying up the area surrounding the grave. This is perfect for those who need regular upkeep of their loved ones' resting places.</p>
            <p><strong>Steps:</strong></p>
            <ul style="text-align: left; padding-left: 20px;">
                <li>Cleaning the grave and surrounding area</li>
                <li>Removing any debris, leaves, or unwanted plants</li>
                <li>Restoring the grave's appearance to its original state</li>
            </ul>
            <p class="price"><strong>₱500</strong></p>
        </div>

        <div class="service-card">
            <h3>Single Service</h3>
            <p>The Single Service allows you to request specific maintenance tasks, such as cleaning the tombstone, fixing grave markers, or clearing overgrown plants. You can select this service for one-time requests whenever you need extra care for the grave.</p>
            <p><strong>Steps:</strong></p>
            <ul style="text-align: left; padding-left: 20px;">
                <li>Custom cleaning of the grave marker</li>
                <li>Fixing or replacing damaged grave markers</li>
                <li>Removing excessive growth of plants or grass</li>
            </ul>
            <p class="price"><strong>₱300</strong></p>
        </div>
    </div>
</section>

<section class="goal-section">
    <h3>Our Goal</h3>
    <p>
        Our goal is to create a system for relatives who are distant from their family members who have passed away and are buried. Since they cannot visit or clean the cemetery where their deceased family members are buried, our system allows these relatives to request cleaning services for the cemetery. 
    </p>
    <p>
        This system ensures that the final resting place of their loved ones remains clean, respected, and well-maintained, even when they are unable to personally visit the site. By providing this service, we aim to honor the memory of those who have passed and give peace of mind to their families.
    </p>
</section>

<footer>
    <p>&copy; 2025 Cemetery Maintenance System | All Rights Reserved</p>
</footer>

</body>
</html>
