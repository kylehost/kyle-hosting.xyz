<?php
session_start();

// Include configuration file
require_once 'config.php';

// Function to handle Google callback
function handleGoogleCallback($gClient) {
    if (isset($_GET['code'])) {
        $gClient->authenticate($_GET['code']);
        $_SESSION['token'] = $gClient->getAccessToken();
        header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
        exit();
    }
}

// Function to handle Spotify callback
function handleSpotifyCallback($clientId, $clientSecret, $redirectUri) {
    if (isset($_GET['code'])) {
        $code = $_GET['code'];
        $tokenUrl = "https://accounts.spotify.com/api/token";
        $tokenData = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
        ];

        $options = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded",
                'method' => 'POST',
                'content' => http_build_query($tokenData),
            ],
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($tokenUrl, false, $context);
        $tokenInfo = json_decode($response);
        return $tokenInfo->access_token;
    }
    return null;
}

// Function to handle Discord callback
function handleDiscordCallback($clientId, $clientSecret, $redirectUri) {
    if (isset($_GET['code'])) {
        $code = $_GET['code'];
        $tokenUrl = "https://discord.com/api/oauth2/token";
        $tokenData = [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'scope' => 'identify email',
        ];

        $options = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded",
                'method' => 'POST',
                'content' => http_build_query($tokenData),
            ],
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($tokenUrl, false, $context);
        $tokenInfo = json_decode($response);
        return $tokenInfo->access_token;
    }
    return null;
}

// Handle Google login
handleGoogleCallback($gClient);

// Handle Spotify login
$spotifyAccessToken = handleSpotifyCallback(SPOTIFY_CLIENT_ID, SPOTIFY_CLIENT_SECRET, SPOTIFY_REDIRECT_URL);

// Handle Discord login
$discordAccessToken = handleDiscordCallback(DISCORD_CLIENT_ID, DISCORD_CLIENT_SECRET, DISCORD_REDIRECT_URL);

// Fetch user information from each service
if ($gClient->getAccessToken()) {
    $google_oauthV2 = new Google_Oauth2Service($gClient);
    $gProfile = $google_oauthV2->userinfo->get();
    $_SESSION['userData']['google'] = $gProfile;
}

if ($spotifyAccessToken) {
    $userInfo = file_get_contents("https://api.spotify.com/v1/me", false, stream_context_create([
        'http' => [
            'header' => "Authorization: Bearer {$spotifyAccessToken}",
        ],
    ]));
    $_SESSION['userData']['spotify'] = json_decode($userInfo);
}

if ($discordAccessToken) {
    $userInfo = file_get_contents("https://discord.com/api/users/@me", false, stream_context_create([
        'http' => [
            'header' => "Authorization: Bearer {$discordAccessToken}",
        ],
    ]));
    $_SESSION['userData']['discord'] = json_decode($userInfo);
}

if (!empty($_SESSION['userData'])) {
    header("Location: home-page.php");
} else {
    echo "Error retrieving user data. Please try again.";
}
?>