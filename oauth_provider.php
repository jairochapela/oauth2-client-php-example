<?php

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();


$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => $_ENV['CLIENT_ID'],    // The client ID assigned to you by the provider
    'clientSecret'            => $_ENV['CLIENT_SECRET'],   // The client password assigned to you by the provider
    'redirectUri'             => $_ENV['REDIRECT_URI'],
    'urlAuthorize'            => $_ENV['URL_AUTHORIZE'],
    'urlAccessToken'          => $_ENV['URL_ACCESS_TOKEN'],
    'urlResourceOwnerDetails' => $_ENV['URL_RESOURCE_OWNER_DETAILS'],
]);

/**
 * Lo que sigue es para forzar a Guzzle a no comprobar la validez del certificado
 * SSL, admitiendo los que son autofirmados. Se trata de un ajuste poco
 * recomendable en entornos de producción.
 */
$guzzyClient = new GuzzleHttp\Client([
    'defaults' => [
        \GuzzleHttp\RequestOptions::CONNECT_TIMEOUT => 5,
        \GuzzleHttp\RequestOptions::ALLOW_REDIRECTS => true],
     \GuzzleHttp\RequestOptions::VERIFY => false,
]);

$provider->setHttpClient($guzzyClient);
