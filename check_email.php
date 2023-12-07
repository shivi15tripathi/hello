<?php
// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$dbname = "auto_generate";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $checkEmailQuery = "SELECT * FROM information WHERE email='$email'";
    $checkEmailResult = mysqli_query($conn, $checkEmailQuery);

    if (mysqli_num_rows($checkEmailResult) > 0) {
        $response = array('available' => false, 'message' => 'Email already exists');
    } else {
        $response = array('available' => true, 'message' => '');
    }

    echo json_encode($response);
}
?>
