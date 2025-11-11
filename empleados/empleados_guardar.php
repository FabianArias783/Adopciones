<?php
require "funciones/conecta.php"; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña
    $rol = $_POST['rol'];

    // Verificar que haya un archivo de foto subido
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fotoOriginal = $_FILES['foto']['name'];
        $fotoTmp = $_FILES['foto']['tmp_name'];
        
        // Generar un nombre único para la foto
        $fotoEncriptada = uniqid() . "_" . basename($fotoOriginal);
        
        // Directorio donde se guardarán las fotos
        $directorioFotos = "fotos_empleados/";
        
        // Asegurarse de que el directorio existe
        if (!is_dir($directorioFotos)) {
            mkdir($directorioFotos, 0755, true); // Crear directorio si no existe
        }
        
        // Mover el archivo al directorio
        if (move_uploaded_file($fotoTmp, $directorioFotos . $fotoEncriptada)) {
            // Conectar a la base de datos
            $con = conecta();
            $sql = "INSERT INTO empleados (nombre, apellidos, correo, pass, rol, foto_nombre_original, foto_nombre_encriptado) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssssiss", $nombre, $apellidos, $correo, $password, $rol, $fotoOriginal, $fotoEncriptada);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $stmt->error]);
            }

            $stmt->close();
            $con->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al mover la foto.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Foto no subida correctamente.']);
    }
}
?>
