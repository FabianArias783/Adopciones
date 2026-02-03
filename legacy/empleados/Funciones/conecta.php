<?php
define("HOST", 'localhost');
define("BD", 'proyecto'); 
define("USER_BD", 'root');
define("PASS_BD", '');

function conecta() {
    $con = new mysqli(HOST, USER_BD, PASS_BD, BD);

    // Verificar si hay algún error en la conexión
    if ($con->connect_error) {
        die("Conexión fallida: " . $con->connect_error);
    }

    return $con;
}
?>
