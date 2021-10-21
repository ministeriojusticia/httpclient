Ministerio de Justicia y Derecho Humanos.
========================

# Ministerio de Justicia y Derecho Humanos.

## Repositorio 

https://packagist.org/packages/mjydh/httpclient


## Instalación del paquete, por ahora la instalación se realiza manualmente.

1 - Descargar el proyecto desde https://github.com/camposgustavoj/httpclient <br>
2 - crear las carpetas mjydh\httpclient dentro de vendor y colocar el paquete descargado dentro. <br>
3 - agregar en el autoload / psr-4 del composer.json del proyecto la referencia al paquete <br>"MJYDH\\HttpClientBundle\\": "vendor/mjydh/httpclient/MJYDH/HttpClientBundle"<br>

"autoload": {<br>
        "psr-4": {<br>
        "MJYDH\\HttpClientBundle\\": "vendor/mjydh/httpclient/MJYDH/HttpClientBundle"<br>
    },<br>
},<br><br>

4 - ejecutar <br>
```bash
composer dump-autoload<br>
```

En caso de no poder ejecutar el dump-autoload (como sucede en adminformel y formularioelectronico), se debe agregar en \vendor\composer\autoload_psr4.php la siguiente linea
'MJYDH\\HttpClientBundle\\' => array($vendorDir . '/mjydh/httpclient/MJYDH/HttpClientBundle'),
<br>
<br>

# Comentarios extras al proyecto 

Versionado - https://semver.org/lang/es/<br>

Dado un número de versión MAYOR.MENOR.PARCHE, se incrementa:

la versión MAYOR cuando realizas un cambio incompatible en el API,
la versión MENOR cuando añades funcionalidad que compatible con versiones anteriores, y
la versión PARCHE cuando reparas errores compatibles con versiones anteriores.
Hay disponibles etiquetas para prelanzamiento y metadata de compilación como extensiones al formato MAYOR.MENOR.PARCHE.