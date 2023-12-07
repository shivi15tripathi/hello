<?php
require 'config.php';
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <style>
      body {
            font-family: 'Arial', sans-serif;
            background-image: url('official_background.jpg'); /* Replace 'official_background.jpg' with the path to your background image */
            background-size: cover;
            background-repeat: no-repeat;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: rgba(255, 255, 255, 0.8); /* Adjust the opacity if needed */
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff; /* Change to your preferred button color */
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        h3 {
            color: #007bff; /* Change to your preferred text color */
        }

        h4 a {
            color: #007bff; /* Change to your preferred link color */
            text-decoration: none;
        }

        h4 a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
     

    <form id="contactForm" action="config.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="contactNumber">Contact Number:</label>
        <input type="tel" id="contactNumber" name="contactNumber" required>

        <label for="whatsappNumber">WhatsApp Number:</label>
        <input type="tel" id="whatsappNumber" name="whatsappNumber">

        <label for="linkedinUrl">LinkedIn URL:</label>
        <input type="url" id="linkedinUrl" name="linkedinUrl">

        <label for="course">Course</label>
        <input type="text" id="course" name="course">

        <button type="submit" name="submit" onclick="validateForm()">Submit</button>
        <h3></h3><h4><a href="generate_certificate.php">send certificate</a><h4>
    </form>

    <script>
        function validateForm() {
            var name = document.getElementById("name").value;
            var email = document.getElementById("email").value;
            var contactNumber = document.getElementById("contactNumber").value;

            // Simple validation example
            if (name === "" || email === "" || contactNumber === "") {
                alert("Please fill in all required fields.");
                return;
            }

            // You can add more complex validations here

            // If all validations pass, you can submit the form or perform other actions
            alert("Form submitted successfully!");
        }
    </script>

</body>
</html>
