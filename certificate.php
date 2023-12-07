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
    define('FPDF_FONTPATH', 'C:/xampp/htdocs/hello/font');
    require('fpdf.php');
    require 'vendor/autoload.php';

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
    $sql = "SELECT candidate_name, course,email FROM information";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $candidateName = $row['candidate_name'];
            $courseName = $row['course'];
            $recipientEmail = $row['email'];

            $certificateContent = "<div class='certificate'>" .
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
            $pdf = new FPDF();

            // Add a new page
            $pdf->AddPage();

            // Set font and size
            $pdf->SetFont('Arial', '', 12);

            // Add HTML content
            $pdf->MultiCell(0, 10, strip_tags($certificateContent));
            $pdfPath = 'certificate.pdf';
            $pdf->Output('F', $pdfPath);
            // Output PDF to the browser or save to a file
            // $pdf->Output('certificate.pdf', 'D');
            ob_end_flush(); 
            // $htmlFile = 'index.html';
            // file_put_contents($htmlFile, $certificateContent);
            // $filename = 'certificate.png';
            // $command = "wkhtmltoimage --crop-w 800 --crop-h 600 --disable-smart-width $htmlFile certificate.png";
            // exec($command);

            // $imageContent = file_get_contents('certificate.png');


            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'shivitripathi151@gmail.com';
                $mail->Password   = 'lrhq ymto hrwm eizt';
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('shivitripathi151@gmail.com', 'shivanitripathi');
                $mail->addAddress($recipientEmail, $candidateName);
                //  


                //Content
                $mail->isHTML(true);
                $mail->Subject = 'Certificate of Achievement';
                $mail->Body = 'Please find attached the Certificate of Achievement.';
                $mail->addAttachment($pdfPath, 'Certificate_of_Achievement.pdf');
                // $mail->addAttachment('$certificateContent', 'Certificate.png', 'base64', 'image/png');

                //$mail->SMTPDebug = 2;  // Enable verbose debug output


                $mail->send();
                echo "Certificate sent successfully to $recipientEmail!<br>";
            } catch (Exception $e) {
                echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}<br>";
            }
            //         unlink($htmlFile);
            // unlink('certificate.png');
        }
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