
# Ejemplo de autenticación OAuth2 en PHP

Este proyecto representa un esquema minimalista de autenticación basado en OAuth2. Consta de las siguientes partes:

* Página inicial (``index.php``), que pretende acceder a un recurso protegido.
* Punto de retorno o _callback_ (``callback.php``), que sirve para que el servidor SSO pueda redirigir a la aplicación con las credenciales concedidas.
* Componente cliente OAuth2 (``oauth2_provider.php``), con el código de inicialización y ajustes de cliente.

## Ajustes

Los ajustes han de incorporarse a un fichero ``.env`` o mediante variables de entorno. Dicho fichero contendrá algo similar a:

```
CLIENT_ID=7
CLIENT_SECRET=R12BoNzCkZ3iRBswHkI83gQfDM0hYBsKA3Zz789Y
REDIRECT_URI=http://localhost:8080/callback.php
URL_AUTHORIZE=http://localhost:8000/oauth/authorize
URL_ACCESS_TOKEN=http://localhost:8000/oauth/token
URL_RESOURCE_OWNER_DETAILS=http://localhost:8000/api/user?client=7
```

## Funcionamiento

Esta demo funciona del siguiente modo:

1. El usuario accede al documento raíz (``index.php``).
2. Al no tener un token de acceso previamente obtenido, es redirigido a la URL de autorización.
3. El servidor SSO socilita autenticación y autorización al usuario para conceder el acceso a la demo.
4. Una vez que el usuario confirma, es redirigido de vuelta a la demo, siendo ``callback.php`` quien procesa dicha petición de retorno.
5. Se obtiene el token de acceso, se almacena en la sesión y se redirige al documento raíz.
6. Ahora ya tiene token de acceso y puede acceder a los datos protegidos, mostrándolos junto con las credenciales obtenidas.



