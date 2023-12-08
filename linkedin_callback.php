<?php
session_start();

// LinkedIn API credentials
$clientID = '86humtt1kqel42';
$clientSecret = '2udXns3paTcQ4T8d';
$redirectURI = 'http://localhost/linkedin_callback.php';

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
    $redirectURI = 'http://localhost/linkedin_callback.php?user_id=' . $userId;

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

// Use the access token to share the certificate on LinkedIn (your existing code)
// ...

// Redirect the user to a success page or their profile page
$successUrl = 'http://localhost/success.php';
header('Location: ' . $successUrl);
exit();
?>

<?php
session_start();

// Assume you have a user ID or some identifier in your session
$userId = $_SESSION['id'];

// GitHub repository information


// Construct the certificate URL on GitHub
$certificateUrl = "https://raw.githubusercontent.com/$githubUsername/$repositoryName/main/download/certificate_$userId.pdf";

// Construct LinkedIn Share URL
$linkedinShareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($certificateUrl);

// Redirect the user to the LinkedIn Share URL
header('Location: ' . $linkedinShareUrl);
exit();
?>

<?php
session_start();

// Replace these values with your database credentials
$host = "localhost";
$username = "root";
$password = "";
$dbname = "auto_generate_certificate";

// Assume you have a user ID or some identifier in your session
$userId = $_SESSION['id'];
$githubUsername = 'shivi15tripathi';
$repositoryName = 'hello';
// Step 1: Retrieve Certificate URL from the database
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$statement = $pdo->prepare("SELECT certificate_url FROM information WHERE id = :id");
$statement->bindParam(':id', $userId, PDO::PARAM_INT);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);

if (!$result || !isset($result['certificate_url'])) {
    echo 'Certificate URL not found in the database for the user.';
    exit();
}

// Step 2: Generate LinkedIn Share URL
$certificateUrl = "https://raw.githubusercontent.com/$githubUsername/$repositoryName/main/download/certificate_$userId.pdf";
$linkedinShareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($certificateUrl);

// Redirect the user to the LinkedIn Share URL
header('Location: ' . $linkedinShareUrl);
exit();
?>
