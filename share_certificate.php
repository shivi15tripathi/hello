<?php
session_start();

// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$dbname = "auto_generate_certificate";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$sql = "SELECT id, candidate_name, course, email, linkedinUrl FROM information";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Assume you have a user ID or some identifier in your session
        $userId = $row['id'];  // Use the user ID from the database record

        // GitHub repository information
        $githubUsername = 'shivi15tripathi';
        $repositoryName = 'hello';

        // Construct the certificate URL on GitHub
        $certificateUrl = "https://raw.githubusercontent.com/$githubUsername/$repositoryName/main/download/certificate_$userId.pdf";


        // Construct LinkedIn Share URL using the share-offsite method
        $linkedinShareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($certificateUrl);

        // Redirect the user to the LinkedIn Share URL
        header('Location: ' . $linkedinShareUrl);
        exit();
    }
} else {
    echo "No records found in the database.";
}
?>
