<?php
// Redirect to LinkedIn for OAuth2 authentication
header('Location: https://www.linkedin.com/oauth/v2/authorization?' . http_build_query([
    'response_type' => 'code',
    'client_id' => 'YOUR_CLIENT_ID',
    'redirect_uri' => 'YOUR_REDIRECT_URI',
    'state' => 'SOME_UNIQUE_STATE',
    'scope' => 'r_liteprofile w_member_social',  // Adjust scopes as needed
]));
exit;
?>
