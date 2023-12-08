<?php
session_start();

// Include necessary libraries and configurations

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

// Validate the user ID from the URL parameter
try {
    if (isset($_GET['id'])) {
        $userId = $_GET['id'];
        // You might want to validate or sanitize the user ID here

        // Fetch user data from the database
        $sql = "SELECT email, linkedinUrl FROM information WHERE id = id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $recipientEmail = $row['email'];
            $recipientLink = $row['linkedinUrl'];

            // Send email with certificate link
            // ... (your existing email sending code)

            $githubUsername = 'shivi15tripathi';
            $repositoryName = 'hello';
            // Redirect to LinkedIn for sharing
            $redirectUrl = 'https://www.linkedin.com/oauth/v2/authorization?client_id=86humtt1kqel42&redirect_uri=https://raw.githubusercontent.com/$githubUsername/$repositoryName/main/linkedin_callback.php';
            $redirectUrl .= '&shareoff=' . urlencode($linkedinShareUrl);
            header('Location: ' . $redirectUrl);
            exit();
        } else {
            echo "User not found.";
        }
    } else {
        echo "Invalid request.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    error_log("Error: " . $e->getMessage(), 0);
}

// Close the database connection
$conn->close();
?>
