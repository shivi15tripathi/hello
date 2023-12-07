<?php
// Share the certificate on LinkedIn
function shareOnLinkedIn($accessToken, $pdfPath) {
    // Use the LinkedIn Share API (previously provided code)
    // ...

    // Example: shareOnLinkedIn($accessToken, $pdfPath);
}

// Example usage
if (isset($_GET['pdf'], $_GET['access_token'])) {
    $pdfPath = $_GET['pdf'];
    $accessToken = $_GET['access_token'];

    // Share on LinkedIn
    shareOnLinkedIn($accessToken, $pdfPath);
} else {
    echo 'Error: PDF path or access token not provided.';
}
?>
