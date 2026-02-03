<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = htmlspecialchars($_POST['nombre']);
    $correo = htmlspecialchars($_POST['correo']);
    $mensaje = htmlspecialchars($_POST['mensaje']);

    $destinatario = "marquitomadrigal19@gmail.com"; 
    $asunto = "Nuevo mensaje de contacto";
    $cuerpo = "Nombre: $nombre\nCorreo: $correo\nMensaje:\n$mensaje";
    $cabeceras = "From: $correo\r\nReply-To: $correo";

    if (mail($destinatario, $asunto, $cuerpo, $cabeceras)) {
        echo "<div class='header-bar'>Correo enviado con éxito</div>";
        echo "<a class='boton-accion' href='contacto_formulario.php'>Regresar</a>";
    } else {
        echo "<div class='header-bar'>Error al enviar el correo</div>";
        echo "<a class='boton-accion' href='contacto_formulario.php'>Intentar de nuevo</a>";
    }
} else {
    echo "Acceso no autorizado.";
}
?>
