<?php
session_start();

// LinkedIn API credentials
$clientID = '86humtt1kqel42';
$clientSecret = '2udXns3paTcQ4T8d';
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

        // Step 3: Use access token to share a post on LinkedIn
        $shareUrl = 'https://api.linkedin.com/v2/shares';
        $shareHeaders = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];
        $certificateUrl = 'http://localhost/hello/download/' . $pdfFilename;

        $shareContent = [
            'content' => [
                'contentEntities' => [
                    [
                        'entityLocation' => $certificateUrl,
                    ],
                ],
                'title' => 'Certificate of Achievement',
                'shareCommentary' => [
                    'text' => 'I have achieved the Certificate of Achievement!',
                ],
            ],
            'distribution' => [
                'linkedInDistributionTarget' => [],
            ],
            'owner' => 'urn:li:person:your-linkedin-user-id', // Replace with the LinkedIn user ID
        ];
        
        

        $shareResponse = makeLinkedInApiRequest($shareUrl, $shareHeaders, 'POST', json_encode($shareContent));

        if (isset($shareResponse['id'])) {
            // Successfully shared the certificate on LinkedIn
            // Redirect the user to a success page or their profile page
            $successUrl = 'http://localhost/success.php';
            header('Location: ' . $successUrl);
            exit();
        } else {
            echo 'Failed to share on LinkedIn.';
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

// Function to make a request to LinkedIn API
function makeLinkedInApiRequest($url, $headers, $method = 'GET', $data = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
?>
