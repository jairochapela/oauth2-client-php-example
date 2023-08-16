<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/oauth_provider.php';


if (isset($_GET['code'])) {   
    // access token
    $accessToken = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // guardamos código de autorización en la sesión
    session_start();

    $_SESSION['token'] = $accessToken;
}

header('Location: /');

