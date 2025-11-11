<?php
?>

<nav style="background-color: #333; padding: 10px 0;">
    <ul style="list-style-type: none; padding: 0; margin: 0; display: flex; justify-content: center; align-items: center;">
        <li style="margin: 0 20px;">
            <a href="home.php" style="color: white; text-decoration: none; font-size: 1.3rem; padding: 10px 15px; display: block;">Home</a>
        </li>
        <li style="margin: 0 20px;">
            <a href="productos_lista_cliente.php" style="color: white; text-decoration: none; font-size: 1.3rem; padding: 10px 15px; display: block;">En adopción</a>
        </li>
        <li style="margin: 0 20px;">
            <a href="carrito_interfaz.php" style="color: white; text-decoration: none; font-size: 1.3rem; padding: 10px 15px; display: block;">Mis adopciones</a>
        </li>

        <?php if (isset($_SESSION['cliente_id'])): ?>
            <!-- Mostrar el nombre del usuario logueado -->
            <li style="margin: 0 20px; color: white;">
                <span style="font-size: 1.3rem;">Bienvenido, <?php echo $_SESSION['cliente_nombre']; ?>!</span>
            </li>
            <!-- Si el usuario está logueado, mostrar el botón de cerrar sesión -->
            <li style="margin: 0 20px;">
                <a href="logout_cliente.php" style="color: white; text-decoration: none; font-size: 1.3rem; padding: 10px 15px; display: block;">Cerrar sesión</a>
            </li>
        <?php else: ?>
            <!-- Si el usuario no está logueado, mostrar el botón de login -->
            <li style="margin: 0 20px;">
                <a href="login_cliente.php" style="color: white; text-decoration: none; font-size: 1.3rem; padding: 10px 15px; display: block;">Log In</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
