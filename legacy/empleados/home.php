<?php
session_start();
include 'menu_cliente.php';
require "funciones/conecta.php";
$con = conecta();

$sql_promocion = "SELECT * FROM promociones WHERE eliminado = 0 ORDER BY RAND() LIMIT 5";
$result_promocion = $con->query($sql_promocion);

// --- CAMBIO AQUÍ: Aumentado el límite de 10 a 15 ---
$sql_productos = "SELECT * FROM productos WHERE eliminado = 0 ORDER BY RAND() LIMIT 15";
$result_productos = $con->query($sql_productos);

if (isset($_SESSION['cliente_id'])) {
    $cliente_id = $_SESSION['cliente_id'];
    $sql_cliente = "SELECT nombre FROM clientes WHERE id = '$cliente_id'";
    $result_cliente = $con->query($sql_cliente);
    $cliente = $result_cliente->fetch_assoc();
    $nombre_cliente = $cliente['nombre'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | Adopta un Amigo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
            width: 90%;
            margin: 20px auto;
        }
        .carousel-inner img {
            object-fit: cover;
            height: 300px;
            border-radius: 10px;
        }
        .carousel-control-prev-icon, .carousel-control-next-icon {
            background-color: #ffb6b9;
        }
        h2 {
            text-align: center;
            color: #ff6f61;
            margin: 30px 0 20px;
            font-weight: bold;
        }
        .productos {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .producto {
            flex: 1 1 calc(20% - 20px); /* 5 por fila */
            max-width: 220px;
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
            max-width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .producto h3 {
            font-size: 1.1rem;
            color: #333;
            margin: 10px 0 5px;
        }
        .producto p {
            font-size: 0.95rem;
            color: #666;
            margin: 2px 0;
        }

        /* --- NUEVO CSS PARA EL FOOTER --- */
        footer {
            background-color: #ffb6b9; /* Mismo color que el header */
            color: #fff;
            padding: 40px 0;
            margin-top: 50px;
            text-align: center;
        }
        footer h3 {
            font-family: 'Pacifico', cursive;
            font-size: 2.2rem;
            margin-bottom: 20px;
        }
        footer p {
            font-size: 1rem;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto 20px auto;
        }
        /* --- FIN DE NUEVO CSS --- */

        @media screen and (max-width: 992px) {
            .producto {
                flex: 1 1 calc(33.33% - 20px);
            }
        }
        @media screen and (max-width: 768px) {
            .producto {
                flex: 1 1 calc(50% - 20px);
            }
        }
        @media screen and (max-width: 576px) {
            .producto {
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <?php if (isset($nombre_cliente)): ?>
            <h1>🐾 ¡Hola <?php echo $nombre_cliente; ?>! Bienvenido a Huellitas Unidas🐶🐱</h1>
        <?php else: ?>
            <h1>🐾 Bienvenido a Huellitas unidas 🐶🐱</h1>
        <?php endif; ?>
    </header>

    <div class="container">
        <div id="promocionesCarrusel" class="carousel slide mb-5" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php 
                $active = "active"; 
                while ($row_promocion = $result_promocion->fetch_assoc()): 
                ?>
                    <div class="carousel-item <?php echo $active; ?>">
                        <img src="uploads_promociones/<?php echo $row_promocion['archivo']; ?>" class="d-block w-100" alt="Promoción">
                    </div>
                    <?php $active = ""; ?>
                <?php endwhile; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#promocionesCarrusel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#promocionesCarrusel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>

        <h2>Amigos en busca de un hogar</h2>
        <div class="productos">
            <?php while ($row_producto = $result_productos->fetch_assoc()): ?>
                <div class="producto">
                    <a href="productos_lista_cliente.php?id=<?php echo $row_producto['id']; ?>">
                        <img src="uploads_productos/<?php echo $row_producto['archivo']; ?>" alt="<?php echo $row_producto['nombre']; ?>">
                    </a>
                    <h3><?php echo $row_producto['nombre']; ?></h3>
                    <p>💖 En adopción</p>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <footer>
        <div class="container">
            <h3>Sobre Nosotros</h3>
            <p>
                En "Huellitas Unidas", creemos que cada animalito merece un hogar lleno de amor y respeto. 
                Somos un equipo de voluntarios apasionados dedicados a rescatar, rehabilitar y encontrar 
                familias permanentes para perros y gatos en situación de abandono.
            </p>
            <p>
                Nuestra misión es conectar a estos maravillosos seres con personas de gran corazón como tú. 
                ¡Gracias por visitarnos y considerar la adopción!
            </p>
        </div>
    </footer>
    </body>
</html>