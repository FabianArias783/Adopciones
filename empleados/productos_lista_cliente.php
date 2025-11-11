<?php
session_start();

if (!isset($_SESSION['cliente_id'])) {
    header("Location: login_cliente.php");
    exit();
}

include 'menu_cliente.php';
include_once 'funciones/conecta.php';
$con = conecta();
$cliente_id = $_SESSION['cliente_id'];

$sql = "SELECT * FROM productos WHERE eliminado = 0 AND (status = 'Disponible' OR status IS NULL)";
$res = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Adopta un Amigo</title>
    <link rel="icon" type="image/png" href="../empleados/uploads_productos/favicon2.png">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #fffaf0;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #ffb6b9;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            font-family: 'Pacifico', cursive;
        }
        header h1 {
            font-size: 2.5rem;
            margin: 0;
        }
        .container {
            width: 95%;
            margin: 30px auto;
        }
        h2 {
            text-align: center;
            color: #ff6f61;
            margin: 30px 0 20px;
            font-weight: bold;
        }
        .productos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            justify-items: center;
        }
        .producto {
            width: 100%;
            max-width: 180px;
            background-color: #fff;
            border: 2px dashed #ffa07a;
            padding: 15px;
            border-radius: 15px;
            text-align: center;
            transition: transform 0.3s;
        }
        .producto:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(255, 160, 122, 0.5);
        }
        .producto img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .producto h3 {
            font-size: 1.1rem;
            color: #333;
            margin: 10px 0 5px;
        }
        .producto .en-adopcion {
            color: #ff6f61;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .producto button {
            background-color: #ffb6b9;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 25px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .producto button:hover {
            background-color: #ff6f61;
        }

        /* Popup */
        .popup {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .popup-content {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            text-align: left;
            border: 2px dashed #ffa07a;
            box-shadow: 0 0 10px rgba(255,160,122,0.5);
            max-height: 90vh;
            overflow-y: auto;
        }
        .popup-content h3 {
            text-align: center;
            color: #ff6f61;
            margin-bottom: 10px;
        }
        .popup-content label {
            font-weight: bold;
            color: #333;
            display: block;
            margin-top: 10px;
        }
        .popup-content input, .popup-content textarea, .popup-content select {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 8px;
            font-family: 'Quicksand', sans-serif;
            resize: none;
        }
        .popup-content button {
            margin-top: 15px;
            background-color: #ffb6b9;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            color: white;
            cursor: pointer;
        }
        .popup-content button:hover {
            background-color: #ff6f61;
        }
    </style>
</head>
<body>
    <header>
        <h1>🐾 Adopta un Amigo Peludo 🐾</h1>
    </header>

    <div class="container">
        <h2>Nuestros Peluditos</h2>
        <div class="productos">
            <?php while ($row = $res->fetch_assoc()): ?>
                <div class="producto">
                    <img src="uploads_productos/<?php echo $row['archivo']; ?>" alt="<?php echo $row['nombre']; ?>">
                    <h3><?php echo $row['nombre']; ?></h3>
                    <div class="en-adopcion">EN ADOPCIÓN</div>
                    <button onclick="abrirPopup(<?php echo $row['id']; ?>)">Solicitar Adopción</button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Popup con formulario completo -->
    <div class="popup" id="popup">
        <div class="popup-content">
            <h3>Formulario de Adopción</h3>
            <form id="formSolicitud">
                <input type="hidden" name="producto_id" id="producto_id">
                
                <label>Numero de Teléfono para contactar (WhatsApp)</label>
                <input type="text" name="telefono" required>

                <label>¿Por qué deseas adoptar?</label>
                <textarea name="motivo" required></textarea>

                <label>¿Has tenido mascotas antes?</label>
                <select name="mascotas_previas" required>
                    <option value="">Selecciona una opción</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>

                <label>¿Dónde vivirá el perro?</label>
                <select name="tipo_vivienda" required>
                    <option value="">Selecciona una opción</option>
                    <option value="casa">Casa</option>
                    <option value="departamento">Departamento</option>
                    <option value="otro">Otro</option>
                </select>

                <label>¿Hay niños o adultos mayores en casa?</label>
                <select name="ninos_mayores" required>
                    <option value="">Selecciona una opción</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>

                <label>¿Tienes espacio exterior (patio, jardín)?</label>
                <select name="espacio_exterior" required>
                    <option value="">Selecciona una opción</option>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select>

                <label>¿Cuántas horas al día pasará sola la mascota?</label>
                <input type="number" name="horas_solo" min="0" max="24" required>

                <label>¿Qué harías si tu perro enfermara o causara daños?</label>
                <textarea name="respuesta_responsabilidad" required></textarea>

                <div style="text-align:center; margin-top:10px;">
                    <button type="submit">Enviar Solicitud</button>
                    <button type="button" onclick="cerrarPopup()">Cancelar</button>
                </div>
            </form>
            <div id="mensaje" style="margin-top:10px; color:#ff6f61; text-align:center;"></div>
        </div>
    </div>

    <script>
        function abrirPopup(id) {
            document.getElementById('producto_id').value = id;
            document.getElementById('popup').style.display = 'flex';
        }
        function cerrarPopup() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('mensaje').innerText = '';
            document.getElementById('formSolicitud').reset();
        }

        document.getElementById('formSolicitud').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('solicitud_guardar.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                document.getElementById('mensaje').innerText = data;
                setTimeout(() => cerrarPopup(), 2500);
            })
            .catch(() => {
                document.getElementById('mensaje').innerText = 'Error al enviar la solicitud.';
            });
        });
    </script>
</body>
</html>
