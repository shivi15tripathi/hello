// try {
        //     // Server settings
        //     $mail->isSMTP();
        //     $mail->Host       = 'smtp.gmail.com';
        //     $mail->SMTPAuth   = true;
        //     $mail->Username   = 'shivitripathi151@gmail.com';
        //     $mail->Password   = 'lrhq ymto hrwm eizt';
        //     $mail->SMTPSecure = 'tls';
        //     $mail->Port       = 587;

        //     // Recipients
        //     $mail->setFrom('shivitripathi151@gmail.com', 'shivanitripathi');
        //     $mail->addAddress($recipientEmail, $candidateName);
        //     // $mail->addAttachment($pdfPath);

        //     // Content
        //     $mail->isHTML(true);
        //     $mail->Subject = 'Certificate of Achievement';
        //     $mail->Body = 'Please find attached the Certificate of Achievement.';
        //     $mail->addAttachment($pdfPath, 'Certificate_of_Achievement.pdf');

        //     // Send the email
        //     $mail->send();
        // } catch (Exception $e) {
        //     echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}<br>";
        // }

        <!-- automatic generation of certificate"  -->
        <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

require('fpdf.php');

header("content-type:image/jpeg");
$font="C:/xampp/htdocs/hello/PlayfairDisplay-Italic-VariableFont_wght.ttf";
$select_query=mysqli_query($conn,"select * from information");
while($certificate=mysqli_fetch_array($select_query))
{
    $image=imagecreatefromjpeg("cert_template.jpg");
    $color=imagecolorallocate($image,19,21,22);
    $name=$certificate['candidate_name'];
    imagettftext($image,96,0,1250,1220,$color,$font,$name);
    imagejpeg($image,"download");
    imagedestroy($image);
}

?>



<!-- --------------------------------------------------------------------- -->
<?php

use TCPDF as TCPDF;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('path/to/PHPMailer/src/PHPMailer.php');
require('path/to/PHPMailer/src/SMTP.php');
require('path/to/PHPMailer/src/Exception.php');

function generateCertificate($candidateName, $courseName, $recipientEmail) {
    // Create a unique PDF filename
    $pdfFilename = 'certificate_' . strtolower(str_replace(' ', '_', $candidateName)) . '.pdf';

    // Certificate content
    $certificateContent = "
        <div style='text-align: center;'>
            <h1>Certificate of Achievement</h1>
            <p>This is to certify that</p>
            <h2>$candidateName</h2>
            <p>has successfully completed the course on</p>
            <h3>$courseName</h3>
            <p>awarded this certificate</p>
        </div>
    ";

    // Create PDF
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

    // Save PDF to a unique path
    $pdfPath = 'C:/xampp/htdocs/hello/download/' . $pdfFilename;
    $pdf->SetProtection($permissions, '', null, 0, null);
    $pdf->Output($pdfPath, 'F');
    ob_end_flush();

    // Send email with the certificate attached
    try {
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com'; // Your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@example.com'; // Your email
        $mail->Password   = 'your_email_password'; // Your email password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('your_email@example.com', 'Your Name');
        $mail->addAddress($recipientEmail, $candidateName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Certificate of Achievement';
        $mail->Body    = 'Please find attached the Certificate of Achievement.';
        $mail->addAttachment($pdfPath, 'Certificate_of_Achievement.pdf');

        // Send the email
        $mail->send();
        
        return $pdfPath; // Return the PDF path after sending email
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return null; // Return null if email sending fails
    }
}

// Example usage:
$candidateName = 'John Doe';
$courseName = 'Web Development';
$recipientEmail = 'john.doe@example.com';
$pdfPath = generateCertificate($candidateName, $courseName, $recipientEmail);

// Now you can use $pdfPath to share on LinkedIn or perform additional actions.
?>
<!-- -------------------------------------------------------------------- -->
<?php
session_start();

// LinkedIn API credentials
$clientID = '78skmfbnjxwxvm';
$clientSecret = 'DbvWFo23yG5HFUUc';
$redirectURI = 'http://localhost/linkedin_callback.php';

// Step 1: Get authorization code from the query parameters
if (isset($_GET['code'])) {
    $authorizationCode = $_GET['code'];

    // Step 2: Exchange authorization code for access token
    $accessTokenUrl = 'https://www.linkedin.com/oauth/v2/accessToken';
    $accessTokenParams = [
        'grant_type' => 'authorization_code',
        'code' => $authorizationCode,
        'redirect_uri' => $redirectURI,
        'client_id' => $clientID,
        'client_secret' => $clientSecret,
    ];

    $accessTokenResponse = getAccessToken($accessTokenUrl, $accessTokenParams);

    if (isset($accessTokenResponse['access_token'])) {
        $accessToken = $accessTokenResponse['access_token'];

        // Step 3: Use access token to get user's LinkedIn profile
        $profileUrl = 'https://api.linkedin.com/v2/me?projection=(id,firstName,lastName)';
        $profileHeaders = [
            'Authorization: Bearer ' . $accessToken,
        ];

        $profileResponse = makeLinkedInApiRequest($profileUrl, $profileHeaders);

        if (isset($profileResponse['id'])) {
            // User's LinkedIn profile ID
            $linkedinUserId = $profileResponse['id'];

            // TODO: Redirect the user to their LinkedIn profile
            $linkedinProfileUrl = 'https://www.linkedin.com/in/' . $linkedinUserId;
            header('Location: ' . $linkedinProfileUrl);
            exit();
        } else {
            echo 'Failed to retrieve LinkedIn profile.';
        }
    } else {
        echo 'Failed to obtain access token.';
    }
} else {
    echo 'Authorization code not found.';
}

// Function to make a POST request to get access token
function getAccessToken($url, $params) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Function to make a GET request to LinkedIn API
function makeLinkedInApiRequest($url, $headers) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
?>

<?php
session_start();

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
$sql = "SELECT id, candidate_name, course, email, linkedinUrl FROM information";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userId = $row['id'];
        $candidateName = $row['candidate_name'];
        $courseName = $row['course'];
        $recipientEmail = $row['email'];
        $recipientLink = $row['linkedinUrl'];

        // Generate unique PDF filename based on user ID
        $pdfFilename = 'certificate_' . $userId . '.pdf';

        // Certificate content
        $certificateContent = "<a href='linkedin_callback.php?user_id=$userId' target='_blank'>View Certificate</a>";
        "<div class='header'><h1 class='title'>Certificate of Achievement</h1></div>".
        "<div class='content'>".
        "<p>This is to certify that</p>".
        "<h2><strong>$candidateName</strong></h2>".
        "<p>has successfully completed the course on</p>".
        "<h3>$courseName</h3>".
        "</div>".
        "<div class='signature'>".
        "<img src='signature.png' alt='Signature'>".
        "<p>John Smith<br>CEO, Example Academy</p>".
        "</div>".
        "</div>";

        // Create PDF
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

        // Save PDF to a unique path
        $pdfPath = 'C:/xampp/htdocs/hello/download/' . $pdfFilename;
        $pdf->SetProtection($permissions, '', null, 0, null);
        $pdf->Output($pdfPath, 'F');
        
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'shivitripathi151@gmail.com';
            $mail->Password   = 'lrhq ymto hrwm eizt';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('shivitripathi151@gmail.com', 'shivanitripathi');
            $mail->addAddress($recipientEmail, $candidateName);

            $mail->isHTML(true);
            $mail->Subject = 'Certificate of Achievement';
            $mail->Body = "Please click the following link to view your Certificate of Achievement: <a href='certificate_handler.php?user_id=$userId' target='_blank'>View Certificate</a>";
            $mail->addAttachment($pdfPath, 'Certificate_of_Achievement.pdf');

            $mail->send();
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}<br>";
        }
    }

    // Exit outside of the while loop
    exit();
} else {
    echo "No records found in the database.";
}

// Close the database connection
$conn->close();
?>
<!-- ---------------------------------------------------------------------- -->
<?php
session_start();

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
$sql = "SELECT id, candidate_name, course, email, linkedinUrl FROM information";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userId = $row['id'];
        $candidateName = $row['candidate_name'];
        $courseName = $row['course'];
        $recipientEmail = $row['email'];
        $recipientLink = $row['linkedinUrl'];

        // Generate unique PDF filename based on user ID
        $pdfFilename = 'certificate_' . $userId . '.pdf';

        // Certificate content
        $certificateContent = "<a href='linkedin_callback.php?user_id=$userId' target='_blank'>View Certificate</a>";
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

        // Create PDF
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

        // Save PDF to a unique path
        $pdfPath = 'C:/xampp/htdocs/hello/download/' . $pdfFilename;
        $pdf->SetProtection($permissions, '', null, 0, null);
        $pdf->Output($pdfPath, 'F');
        ob_end_flush();

        // Convert PDF to PNG using ImageMagick
        $pngPath = 'C:/xampp/htdocs/hello/download/' . $userId . '.png';
        exec("convert -density 150 {$pdfPath}[0] {$pngPath}");

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'shivitripathi151@gmail.com';
            $mail->Password   = 'lrhq ymto hrwm eizt';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('shivitripathi151@gmail.com', 'shivanitripathi');
            $mail->addAddress($recipientEmail, $candidateName);

            $mail->isHTML(true);
            $mail->Subject = 'Certificate of Achievement';
            $mail->Body = "Please click the following link to view your Certificate of Achievement: <a href='certificate_handler.php?user_id=$userId' target='_blank'>View Certificate</a>";
            $mail->addAttachment($pngPath, 'Certificate_of_Achievement.png');

            $mail->send();
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}<br>";
        }

        // Free up memory
        unlink($pdfPath); // Remove the PDF file
        unlink($pngPath); // Remove the PNG file

        exit();
    }
} else {
    echo "No records found in the database.";
}

// Close the database connection
$conn->close();
?>
<!-- -------------------------------------------------------------------------------- -->
// $shareContent = [
        //     'content' => [
        //         'contentEntities' => [
        //             [
        //                 'entityLocation' => $certificateUrl,
        //                 'thumbnails' => [
        //                     [
        //                         'resolvedUrl' => 'http://your-thumbnail-url.com', // Replace with the actual URL of your certificate thumbnail
        //                     ],
        //                 ],
        //             ],
        //         ],
        //         'title' => 'Certificate of Achievement',
        //         'shareCommentary' => [
        //             'text' => 'I have achieved the Certificate of Achievement!',
        //         ],
        //     ],
        //     'distribution' => [
        //         'linkedInDistributionTarget' => [],
        //     ],
        //     'owner' => 'urn:li:person:your-linkedin-user-id', // Replace with the LinkedIn user ID
        // ];