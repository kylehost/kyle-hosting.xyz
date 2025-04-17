<?php
session_start();
require_once 'config.php'; // Include your configuration file

// Function to generate OAuth URLs
function generateOAuthUrl($service) {
    switch ($service) {
        case 'google':
            return "https://accounts.google.com/o/oauth2/auth?client_id=" . GOOGLE_CLIENT_ID . "&redirect_uri=" . GOOGLE_REDIRECT_URL . "&response_type=code&scope=email profile";
        case 'spotify':
            return "https://accounts.spotify.com/authorize?client_id=" . SPOTIFY_CLIENT_ID . "&redirect_uri=" . SPOTIFY_REDIRECT_URL . "&response_type=code&scope=user-read-email";
        case 'discord':
            return "https://discord.com/api/oauth2/authorize?client_id=" . DISCORD_CLIENT_ID . "&redirect_uri=" . DISCORD_REDIRECT_URL . "&response_type=code&scope=identify email";
        default:
            return '#';
    }
}

// Check if the user is already logged in
if (isset($_SESSION['userData'])) {
    header("Location: home-page.php"); // Redirect to home if already logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file for styling -->
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <p>Please choose a login method:</p>
        <div class="oauth-buttons">
            <a href="<?php echo generateOAuthUrl('google'); ?>" class="oauth-button google">Login with Google</a>
            <a href="<?php echo generateOAuthUrl('spotify'); ?>" class="oauth-button spotify">Login with Spotify</a>
            <a href="<?php echo generateOAuthUrl('discord'); ?>" class="oauth-button discord">Login with Discord</a>
        </div>
        <p class="error-message">
            <?php
            // Display error messages if any
            if (isset($_SESSION['error_message'])) {
                echo htmlspecialchars($_SESSION['error_message']);
                unset($_SESSION['error_message']); // Clear the message after displaying
            }
            ?>
        </p>
    </div>
</body>
</html>