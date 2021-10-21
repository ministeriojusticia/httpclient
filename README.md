# Ministerio de Justicia y Derecho Humanos.

## Repositorio 

https://packagist.org/packages/mjydh/httpclient


## Instalación del paquete, por ahora la instalación se realiza manualmente.

1 - Descargar el proyecto desde https://github.com/camposgustavoj/httpclient <br>
2 - crear las carpetas mjydh\httpclient dentro de vendor y colocar el paquete descargado dentro. <br>
3 - agregar en el autoload / psr-4 del composer.json del proyecto la referencia al paquete <br>"MJYDH\\\\HttpClientBundle\\\\": "vendor/mjydh/HttpClientBundle"<br>

"autoload": {<br>
        "psr-4": {<br>
        "MJYDH\\\\HttpClientBundle\\\\": "vendor/mjydh/HttpClientBundle"<br>
    },<br>
},<br><br>

4 - ejecutar <br>
```bash
composer dump-autoload<br>
```

En caso de no poder ejecutar el dump-autoload (como sucede en adminformel y formularioelectronico), se debe agregar en \vendor\composer\autoload_psr4.php la siguiente linea
'MJYDH\\\\HttpClientBundle\\\\' => array($vendorDir . '/mjydh/HttpClientBundle'),
<br>
5 - Agregar en el AppKernel.php<br>

```php
new MJYDH\HttpClientBundle\HttpClientBundle(),
```
<br>

## Como implementarlo

```php
// Http client 
use MJYDH\HttpClientBundle\Service\HttpClient;
use MJYDH\HttpClientBundle\Exception\HttpException;
use MJYDH\HttpClientBundle\Service\CatchExceptions;


try
{
    $http = new HttpClient();
    $http->setAuth($user, $pass);
    $http->setHttpCodeResponses(array(200));
    $http->setCatchExceptions(array(502=> new CatchExceptions("Error 502", "titulo 502"), 
                                    0=> new CatchExceptions("Error 0")));


    $result = $http->Execute('GET', $url);      

}
catch (HttpException $ehttp){return $this->showError($ehttp->getMessage(), $ehttp->getTitle()); }
```

# Comentarios extras al proyecto 

Versionado - https://semver.org/lang/es/<br>

Dado un número de versión MAYOR.MENOR.PARCHE, se incrementa:

la versión MAYOR cuando realizas un cambio incompatible en el API,
la versión MENOR cuando añades funcionalidad que compatible con versiones anteriores, y
la versión PARCHE cuando reparas errores compatibles con versiones anteriores.
Hay disponibles etiquetas para prelanzamiento y metadata de compilación como extensiones al formato MAYOR.MENOR.PARCHE.