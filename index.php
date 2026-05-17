<?php
if (session_status() === PHP_SESSION_NONE){
    session_start();
}

if (!isset($_SESSION['usuario_id']) && isset($_COOKIE['rm_token'])) {
    include 'php/config/conexion.php';

    $token = $_COOKIE['rm_token'];

    $sql = "SELECT id, nombre, rol FROM usuarios WHERE sesion_token = :token LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':token' => $token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];
    } else {
        setcookie('rm_token', '', time() - 3600, "/");
    }
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$ruta_base = ''; 
include 'php/includes/header_index.php';
?>

    <main class="contenedor-principal">
        
        <?php include 'php/includes/sidebar_index.php'; ?>

        <section class="contenido">
            <article class="bienvenida">
                <h1>Bienvenido al Sistema de Inventario y Ventas</h1>
                <p>Este sistema permite gestionar productos, proveedores y clientes cumpliendo con los requisitos integradores de Back-End y Front-End.</p>
                
                <div class="info-proyecto">
                    <h3>Estado del Sistema:</h3>
                    <ul>
                        <li><strong>CRUDs:</strong> En desarrollo (Módulo Productos listo).</li>
                        <li><strong>Base de Datos:</strong> Conectada.</li>
                        <li><strong>Apariencia:</strong> CSS Puro.</li>
                    </ul>
                </div>
            </article>
            <form action="php/acciones/logout.php" method="POST">
                <button type="submit" class="btn-logout">Cerrar Sesion</button>

            </form>
        </section>

    </main>

    <?php include 'php/includes/footer.php'; ?>

    <script src="js/validaciones.js"></script>
</body>
</html>