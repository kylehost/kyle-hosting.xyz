<?php
session_start();

require_once '../config.php';

require_once 'local/db.php';

function handleDiscordCallback() {
    if (isset($_GET['code'])) {
        $tokenResponse = file_get_contents("https://discord.com/api/oauth2/token", false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query([
                    'client_id' => DISCORD_CLIENT_ID,
                    'client_secret' => DISCORD_CLIENT_SECRET,
                    'grant_type' => 'authorization_code',
                    'code' => $_GET['code'],
                    'redirect_uri' => DISCORD_REDIRECT_URL,
                ]),
            ],
        ]));
        $tokenData = json_decode($tokenResponse, true);
        $_SESSION['discord_token'] = $tokenData['access_token'];
        header('Location: ' . filter_var(DISCORD_REDIRECT_URL, FILTER_SANITIZE_URL));
        exit();
    }
}

function handleSpotifyCallback() {
    if (isset($_GET['code'])) {
        $tokenResponse = file_get_contents("https://accounts.spotify.com/api/token", false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query([
                    'client_id' => SPOTIFY_CLIENT_ID,
                    'client_secret' => SPOTIFY_CLIENT_SECRET,
                    'grant_type' => 'authorization_code',
                    'code' => $_GET['code'],
                    'redirect_uri' => SPOTIFY_REDIRECT_URL,
                ]),
            ],
        ]));
        $tokenData = json_decode($tokenResponse, true);
        $_SESSION['spotify_token'] = $tokenData['access_token'];
        header('Location: ' . filter_var(SPOTIFY_REDIRECT_URL, FILTER_SANITIZE_URL));
        exit();
    }
}
handleDiscordCallback();
handleSpotifyCallback();
if (isset($_SESSION['discord_token'])) {
    $userResponse = file_get_contents("https://discord.com/api/users/@me", false, stream_context_create([
        'http' => [
            'header' => "Authorization: Bearer " . $_SESSION['discord_token'],
        ],
    ]));
    $discordUser Info = json_decode($userResponse, true);

    global $db;

    $discordId = $discordUser Info['id'];
    $discordEmail = $discordUser Info['email'];
    $discordName = $discordUser Info['username'];

    $stmt = $db->prepare("SELECT * FROM users WHERE discord_id = :discord_id");
    $stmt->bindParam(':discord_id', $discordId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
    } else {
        $stmt = $db->prepare("INSERT INTO users (discord_id, email, name) VALUES (:discord_id, :email, :name)");
        $stmt->bindParam(':discord_id', $discordId);
        $stmt->bindParam(':email', $discordEmail);
        $stmt->bindParam(':name', $discordName);
        $stmt->execute();

        $_SESSION['user_id'] = $db->lastInsertId();
        $_SESSION['user_name'] = $discordName;
    }

    header("Location: index.php");
    exit();
} else {
    $_SESSION['error_message'] = "Unable to retrieve user information from Discord.";
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['spotify_token'])) {
    $userResponse = file_get_contents("https://api.spotify.com/v1/me", false, stream_context_create([
        'http' => [
            'header' => "Authorization: Bearer " . $_SESSION['spotify_token'],
        ],
    ]));
    $spotifyUser Info = json_decode($userResponse, true);

    global $db;

    $spotifyId = $spotifyUser Info['id'];
    $spotifyEmail = $spotifyUser Info['email'];
    $spotifyName = $spotifyUser Info['display_name'];

    $stmt = $db->prepare("SELECT * FROM users WHERE spotify_id = :spotify_id");
    $stmt->bindParam(':spotify_id', $spotifyId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
    } else {
        $stmt = $db->prepare("INSERT INTO users (spotify_id, email, name) VALUES (:spotify_id, :email, :name)");
        $stmt->bindParam(':spotify_id', $spotifyId);
        $stmt->bindParam(':email', $spotifyEmail);
        $stmt->bindParam(':name', $spotifyName);
        $stmt->execute();

        $_SESSION['user_id'] = $db->lastInsertId();
        $_SESSION['user_name'] = $spotifyName;
    }

    header("Location: index.php");
    exit();
} else {
    $_SESSION['error_message'] = "Unable to retrieve user information from Spotify.";
    header("Location: login.php");
    exit();
}