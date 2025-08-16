<?php

// Fichero de configuración de la base de datos
// Define las credenciales de acceso

// Host de la base de datos (normalmente 'localhost' en XAMPP)
define('DB_HOST', 'localhost');

// Usuario de la base de datos (normalmente 'root' en XAMPP)
define('DB_USER', 'root');

// Contraseña del usuario de la base de datos (normalmente vacía en XAMPP)
define('DB_PASSWORD', '');

// Nombre de la base de datos, según el documento de requisitos
define('DB_NAME', 'centro_impresion');

// --- Proceso de Conexión ---

// Crear una nueva conexión a la base de datos usando la extensión MySQLi
$connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Comprobar si la conexión ha fallado
if ($connection->connect_error) {
    // Si hay un error, se detiene la ejecución del script y se muestra el error
    // Es una medida de seguridad y depuración para la fase de desarrollo
    die("Error de Conexión: " . $connection->connect_error);
}

// Establecer el conjunto de caracteres a UTF-8 para soportar tildes y caracteres especiales
if (!$connection->set_charset("utf8")) {
    printf("Error cargando el conjunto de caracteres utf8: %s\n", $connection->error);
    exit();
}

// Si todo ha ido bien, el script continúa y la variable $connection
// queda disponible para ser usada en otras partes del proyecto.

?>