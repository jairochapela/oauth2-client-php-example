<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/oauth_provider.php';


session_start();

// If we don't have an authorization code then get one
if (!isset($_SESSION['token'])):
        
        // Fetch the authorization URL from the provider; this returns the
        // urlAuthorize option and generates and applies any necessary parameters
        // (e.g. state).
        $authorizationUrl = $provider->getAuthorizationUrl();
        
        // Get the state generated for you and store it to the session.
        $_SESSION['oauth2state'] = $provider->getState();
        
        // Redirect the user to the authorization URL.
        header('Location: ' . $authorizationUrl);
        exit;
        
//         // Check given state against previously stored one to mitigate CSRF attack
// elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])):
        
//         if (isset($_SESSION['oauth2state'])) {
//                 unset($_SESSION['oauth2state']);
//         }
        
//         exit('Invalid state');
        
else: ?>
<html>
<head>
    <title>Aplicación de prueba</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>

<?php
if (isset($_SESSION['token'])):     
    // access token
//     $accessToken = $provider->getAccessToken('authorization_code', [
//         'code' => $_SESSION['token']
//     ]);
        $accessToken = $_SESSION['token'];
?>

<div class="container">
    <h1>Acceso concedido</h1>
    <h3>Credenciales</h3>
    
    <h5>Token de acceso</h5>
    <code><?= $accessToken->getToken() ?></code>
        
    <h5>Token de refresco</h5>
    <code><?= $accessToken->getRefreshToken() ?></code>

    <h5>Caducidad</h5>
    <p>
        Fecha de expiración: <?= $accessToken->getExpires() ?>
        <?php if ($accessToken->hasExpired()): ?>
            <span class="badge badge-danger">Expirado</span>
        <?php else: ?>
            <span class="badge badge-success">Vigente</span>
        <?php endif; ?>
    </p>

    <h3>Datos del usuario</h3>
    <?php
            try {
                // resource owner.
                $resourceOwner = $provider->getResourceOwner($accessToken);
                $userdata = $resourceOwner->toArray();
            
                echo 'Username: ' . $userdata['name'] . "<br>";
                echo 'UserID: ' . $userdata['id'] . "<br>";
                echo 'Email: ' . $userdata['email'] . "<br>";
                echo 'Fecha de creación: ' . $userdata['created_at'] . "<br>";
                echo 'Fecha de actualización: ' . $userdata['updated_at'] . "<br>";    

                var_dump($userdata);
            } catch(\Exception $ex) {
                echo 'Error obteniendo datos de usuario: '. $ex->getMessage() .'<br>';
            }
    ?>

    <h3>Acciones</h3>
    <a class="btn btn-primary" href="/" role="button">Volver a solicitar acceso</a>
    <a class="btn btn-secondary" href="logout.php">Cerrar sesión SSO</a>
</div>
<?php else: ?>
    <div class="content">
        <h1>Acceso denegado</h1>
        <a href="/">Volver a solicitar acceso</a>
    </div>
    <?php endif; ?>
</body>
</html>
<?php endif; ?>
        
        


