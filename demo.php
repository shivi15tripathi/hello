
<?php
session_start();

// Check if the user is authenticated
if (isset($_SESSION['user_authenticated']) && $_SESSION['user_authenticated'] === true) {
    // Display the PDF
    header('Content-type: application/pdf');
    readfile($_SESSION['pdf_path']);
    exit(); // Terminate the script after displaying the PDF
}

// Continue with the rest of your HTML and PHP code below...
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dummy Certificate</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .certificate {
            width: 800px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
        }

        .title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .content {
            text-align: center;
            margin-bottom: 20px;
        }

        .signature {
            text-align: center;
            margin-top: 30px;
        }

        .signature img {
            width: 150px;
            height: auto;
            margin-top: 10px;
        }
    </style>
</head>

<body>

<?php

require('fpdf.php');
require 'vendor/autoload.php';

use TCPDF as TCPDF;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

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
$sql = "SELECT candidate_name, course, email,linkedinUrl FROM information";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $candidateName = $row['candidate_name'];
        $courseName = $row['course'];
        $recipientEmail = $row['email'];
        $recipientlink=$row['linkedinUrl'];

        $certificateContent = "<a href='$recipientlink' target='_blank'>View Certificate</a>";
            "<div class='certificate'>" .
            "<div class='header'><h1 class='title'>Certificate of Achievement</h1></div>" .
            "<div class='content'>" .
            "<p>This is to certify that</p>" .
            "<h2><strong>$candidateName</strong></h2>" .
            "<p>has successfully completed the course on</p>" .
            "<h3>$courseName</h3>" .
            "</div>" .
            "<div class='signature'>" .
            "<img src='signature.png' alt='Signature'>" .
            "<p>John Smith<br>CEO, Example Academy</p>" .
            "</div>" .
            "</div>";

        
            ob_start();
            $pdf = new TCPDF();
            $pdf->AddPage();
            $pdf->SetFont('helvetica', '', 12);
            $pdf->writeHTML($certificateContent, true, false, true, false, '');
            $permissions = array(
                'print',
                'copy',
                'modify',
                'annot-forms'
            );
    
            // Generate a unique PDF path for each client
            // $pdfPath = 'C:/xampp/htdocs/hello/download/certificate_' . strtolower(str_replace(' ', '_', $candidateName)) . '.pdf';
            $pdfPath = 'C:/xampp/htdocs/hello/download/certificate.pdf';
            $pdf->SetProtection($permissions, '', null, 0, null);
            $pdf->Output($pdfPath, 'F');
            ob_end_flush();

        
        try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'shivitripathi151@gmail.com';
    $mail->Password   = 'lrhq ymto hrwm eizt';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('shivitripathi151@gmail.com', 'shivanitripathi');
    $mail->addAddress($recipientEmail, $candidateName);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Certificate of Achievement';
    $linkedinShareURL = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode('https://your-certificate-url-here.com');
    $mail->Body = 'Please find attached the Certificate of Achievement. You can also share your achievement on LinkedIn: <a href="' . $linkedinShareURL . '" target="_blank">Share on LinkedIn</a>';
    $mail->addAttachment($pdfPath, 'Certificate_of_Achievement.pdf');

    // Send the email
    $mail->send();
} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}<br>";
}

    }

    // Output LinkedIn share link with Font Awesome icon
   
    echo "<br><a href='$linkedinShareURL' target='_blank'><i class='fab fa-linkedin'></i></a>";
// 
} else {
    echo "No records found in the database.";
}

// Close the database connection
$conn->close();
?>



    <div class="certificate">
        <div class="header">
            <h1 class="title">Certificate of Achievement</h1>
        </div>
        <div class="content">
            <p>This is to certify that</p>
            <h2><strong><?php echo $candidateName; ?></strong></h2>
            <p>has successfully completed the course on</p>
            <h3><?php echo $courseName; ?></h3>
            <p>awarded this</p>
        </div>
        <div class="signature">
            <!-- Placeholder signature image (replace with your actual signature image) -->
            <img src="signature.png" alt="Signature">
            <p>John Smith<br>CEO, Example Academy</p>
        </div>
    </div>

</body>

</html>
<!-- ------------------------------------------------------------------- -->