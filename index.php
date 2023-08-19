<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/oauth_provider.php';


session_start();

// If we don't have an authorization code then get one
if (!isset($_SESSION['token'])) {
        

    
        // Fetch the authorization URL from the provider; this returns the
        // urlAuthorize option and generates and applies any necessary parameters
        // (e.g. state).
        $authorizationUrl = $provider->getAuthorizationUrl();
        
        // Get the state generated for you and store it to the session.
        $_SESSION['oauth2state'] = $provider->getState();
        
        // Redirect the user to the authorization URL.
        // header('Location: ' . $authorizationUrl);
        // exit;
        
//         // Check given state against previously stored one to mitigate CSRF attack
// elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])):
        
//         if (isset($_SESSION['oauth2state'])) {
//                 unset($_SESSION['oauth2state']);
//         }
        
//         exit('Invalid state');
        
} else {
//    $accessToken = $provider->getAccessToken('authorization_code', [
//         'code' => $_SESSION['token']
//    ]);

$accessToken = $_SESSION['token'];

   if ($accessToken->hasExpired()) {
        $newAccessToken = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $accessToken->getRefreshToken()
        ]);
        $_SESSION['token'] = $newAccessToken;
        // $authorizationUrl = $provider->getAuthorizationUrl();

        // header('Location: ' . $authorizationUrl);
        // exit;
   }
} ?>
<html>
<head>
    <title>Aplicación de prueba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        /* Background styles */
        html {
            background-color: black;
        }
        
        body {
            background-image: radial-gradient(circle at 69% 75%, hsla(65,0%,95%,0.05) 0%, hsla(65,0%,95%,0.05) 38%,transparent 38%, transparent 69%,transparent 69%, transparent 100%),radial-gradient(circle at 41% 58%, hsla(65,0%,95%,0.05) 0%, hsla(65,0%,95%,0.05) 3%,transparent 3%, transparent 75%,transparent 75%, transparent 100%),radial-gradient(circle at 94% 91%, hsla(65,0%,95%,0.05) 0%, hsla(65,0%,95%,0.05) 48%,transparent 48%, transparent 55%,transparent 55%, transparent 100%),radial-gradient(circle at 68% 38%, hsla(65,0%,95%,0.05) 0%, hsla(65,0%,95%,0.05) 34%,transparent 34%, transparent 36%,transparent 36%, transparent 100%),radial-gradient(circle at 81% 20%, hsla(65,0%,95%,0.05) 0%, hsla(65,0%,95%,0.05) 40%,transparent 40%, transparent 61%,transparent 61%, transparent 100%),radial-gradient(circle at 46% 37%, hsla(65,0%,95%,0.05) 0%, hsla(65,0%,95%,0.05) 37%,transparent 37%, transparent 76%,transparent 76%, transparent 100%),radial-gradient(circle at 49% 5%, hsla(65,0%,95%,0.05) 0%, hsla(65,0%,95%,0.05) 43%,transparent 43%, transparent 67%,transparent 67%, transparent 100%),radial-gradient(circle at 18% 58%, hsla(65,0%,95%,0.05) 0%, hsla(65,0%,95%,0.05) 4%,transparent 4%, transparent 20%,transparent 20%, transparent 100%),radial-gradient(circle at 43% 68%, hsla(65,0%,95%,0.05) 0%, hsla(65,0%,95%,0.05) 10%,transparent 10%, transparent 36%,transparent 36%, transparent 100%),linear-gradient(135deg, rgb(85, 133, 238),rgb(177, 145, 214));
        .glass {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.75);            
        }

        /* Glassmorphism card effect */
        @media screen and (min-width: 480px) {
                
            
            .card {
                backdrop-filter: blur(16px) saturate(180%);
                -webkit-backdrop-filter: blur(16px) saturate(180%);
                background-color: rgba(255, 255, 255, 0.75);
                border-radius: 12px;
                border: 1px solid rgba(209, 213, 219, 0.3);
                box-shadow: 4px 1px 8px;
            }
        }

        footer {
            background: black;
            color: gray;
        }
        
    </style>    
</head>
<body>

<?php
if (isset($_SESSION['token'])){
    // access token
//     $accessToken = $provider->getAccessToken('authorization_code', [
//         'code' => $_SESSION['token']
//     ]);
        $accessToken = $_SESSION['token'];
} else {
    $accessToken = null;
}
?>

<nav class="navbar navbar-expand-lg glass">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Aplicación de Prueba</a>

        <ul class="navbar-nav">
            <?php if (!$accessToken || $accessToken->hasExpired()): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= $provider->getAuthorizationUrl() ?>">Iniciar sesión</a>
            </li>
            <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= $_ENV['URL_SSO_LOGOUT'] ?>?post_logout_redirect_uri=<?= urlencode($_ENV['LOGOUT_REDIRECT_URI']) ?>&client_id=<?= $_ENV['CLIENT_ID'] ?>">Cerrar sesión</a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>




<?php if ($accessToken && !$accessToken->hasExpired()): ?>

<div class="container card mt-3">
    <h1>Acceso concedido</h1>
    <h3>Credenciales</h3>
    
    <h5>Token de acceso</h5>
    <code><?= $accessToken->getToken() ?></code>
        
    <h5>Token de refresco</h5>
    <code><?= $accessToken->getRefreshToken() ?></code>

    <h5>Caducidad</h5>
    <p>
        Fecha de expiración: <?= (new DateTime('@'.$accessToken->getExpires()))->format(DATE_RFC2822) ?>
        <?php if ($accessToken->hasExpired()): ?>
            <span class="badge text-bg-danger">Expirado</span>
        <?php else: ?>
            <span class="badge text-bg-success">Vigente</span>
        <?php endif; ?>
    </p>

</div>
<?php else: ?>
    <div class="container card mt-3">
        <h1>Acceso restringido</h1>
        <p>
            Para acceder a esta página es necesario autenticarse.
        </p>
    </div>
    <?php endif; ?>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa" crossorigin="anonymous"></script>    
</body>
</html>        
        


