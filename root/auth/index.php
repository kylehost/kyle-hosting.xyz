<?php
session_start();

// Google login
$googleClientId = '';
$googleRedirectUri = '';
$googleAuthUrl = "https://accounts.google.com/o/oauth2/auth?client_id={$googleClientId}&redirect_uri={$googleRedirectUri}&response_type=code&scope=email";

// Spotify login
$spotifyClientId = '';
$spotifyRedirectUri = '';
$spotifyAuthUrl = "https://accounts.spotify.com/authorize?client_id={$spotifyClientId}&redirect_uri={$spotifyRedirectUri}&response_type=code&scope=user-read-email";

// Discord login
$discordClientId = '';
$discordRedirectUri = '';
$discordAuthUrl = "https://discord.com/api/oauth2/authorize?client_id={$discordClientId}&redirect_uri={$discordRedirectUri}&response_type=code&scope=identify%20email";

echo "<a href='{$googleAuthUrl}'>Login with Google</a><br>";
echo "<a href='{$spotifyAuthUrl}'>Login with Spotify</a><br>";
echo "<a href='{$discordAuthUrl}'>Login with Discord</a><br>";
?>