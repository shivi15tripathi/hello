<?php
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
if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contactNumber = $_POST['contactNumber'];
    $whatsappNumber = $_POST['whatsappNumber'];
    $linkedinUrl =$_POST['linkedinUrl'];
    $course=$_POST['course'];

// SQL query to insert data into the database
$sql = "INSERT INTO information (candidate_name, email, contactNumber, whatsappNumber, linkedinUrl,course)
        VALUES ('$name', '$email', '$contactNumber','$whatsappNumber', '$linkedinUrl','$course')";

// Check if the query was successful
if ($conn->query($sql) === TRUE) {
    echo "Form submitted successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}
// Close the database connection
$conn->close();
?>
