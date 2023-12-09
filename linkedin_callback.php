<?php
session_start();

// LinkedIn API credentials
$clientID = '86humtt1kqel42';
$clientSecret = '2udXns3paTcQ4T8d';
$redirectURI = 'https://www.linkedin.com/';

// Replace these values with your database credentials
$host = "localhost";
$username = "root";
$password = "";
$dbname = "auto_generate_certificate";

// Assume you have a user ID or some identifier in your session
$userId = $_SESSION['id'];

// Step 1: Retrieve LinkedIn URL from the database
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$statement = $pdo->prepare("SELECT linkedinurl FROM information WHERE id = :id");
$statement->bindParam(':id', $userId, PDO::PARAM_INT);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);

if (!$result || !isset($result['linkedinurl'])) {
    echo 'LinkedIn URL not found in the database for the user.';
    exit();
}

// Step 2: Generate LinkedIn Share URL
$githubUsername = 'shivi15tripathi';
$repositoryName = 'hello';
$userId = $_SESSION['id'];

// Construct the certificate URL on GitHub
$certificateUrl = "https://raw.githubusercontent.com/$githubUsername/$repositoryName/main/download/certificate_$userId.pdf";
$linkedinShareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($certificateUrl);

// Step 3: Initiate LinkedIn OAuth flow only if the user hasn't authorized yet
if (!isset($_SESSION['linkedin_access_token'])) {
    // Dynamic Redirect URI including user ID
    $redirectURI = 'https://www.linkedin.com/' . $userId;

    // Step 4: Redirect the user to LinkedIn for authorization
    $authUrl = 'https://www.linkedin.com/oauth/v2/authorization';
    $authUrl .= '?response_type=code';
    $authUrl .= '&client_id=' . $clientID;
    $authUrl .= '&redirect_uri=' . urlencode($redirectURI);
    $authUrl .= '&scope=r_liteprofile%20w_member_social';

    // Redirect the user to the LinkedIn Authorization URL
    header('Location: ' . $authUrl);
    exit();
}

// The user has already authorized, proceed to share the certificate on LinkedIn

// Fetch the LinkedIn access token from the session
$accessToken = $_SESSION['linkedin_access_token'];

// Share a post on LinkedIn using the LinkedIn API
$shareUrl = 'https://api.linkedin.com/v2/shares';
$shareContent = json_encode([
    'content' => [
        'contentEntities' => [
            [
                'entityLocation' => $certificateUrl,
            ],
        ],
        'title' => 'Check out my certificate!',
        'description' => 'I earned a certificate. Click the link to view.',
    ],
    'distribution' => [
        'linkedInDistributionTarget' => [],
    ],
]);

$ch = curl_init($shareUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $shareContent);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $accessToken,
]);

$response = curl_exec($ch);
curl_close($ch);

// Redirect the user to a success page or their profile page
$successUrl = 'http://localhost/success.php';
header('Location: ' . $successUrl);
exit();
?>
