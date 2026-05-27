<?php
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Si ya tiene la sesión normal de PHP activa, lo mandamos al index directo
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

// 2. Si NO tiene sesión pero SÍ tiene la cookie 'rm_token', intentamos loguearlo en segundo plano
if (!isset($_SESSION['usuario_id']) && isset($_COOKIE['rm_token'])) {
    // Como estás en la raíz, la ruta a la conexión es esta:
    include 'php/config/conexion.php';

    $token = $_COOKIE['rm_token'];

    // Buscamos al usuario por el token de la cookie
    $sql = "SELECT id, nombre, rol FROM usuarios WHERE sesion_token = :token LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':token' => $token]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Token válido: Reconstruimos la sesión acá mismo
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];

        // Lo mandamos al index sin mostrar el formulario
        header("Location: index.php");
        exit();
    } else {
        // Si el token era viejo o inválido, limpiamos la cookie para no procesarla más
        setcookie('rm_token', '', time() - 3600, "/");
    }
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kiosko Manager</title>
    <link rel="stylesheet" href="css/estilos.css?v=11">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            
            <div class="login-logo">
                <img src="img/icono-tienda.svg" alt="Logo">
                <h1>Kiosko Manager</h1>
            </div>
            <form action="php/acciones/procesar_login.php" method="POST" class="formulario" id="formLogin" novalidate>
                <div class="campo">
                    <label>Correo Electrónico:</label>
                    <input type="email" name="email" id="inputEmail" placeholder="ejemplo@correo.com">
                </div>

                <div class="campo">
                    <label>Contraseña:</label>
                    <input type="password" name="password" id="inputPassword" placeholder="••••••••">
                </div>

                <div class="campo-cb" >
                    <input type="checkbox" name="mantener_sesion_abierta" id="cb-sesion">
                    <label for="cb-sesion">Mantener Sesión abierta</label>
                </div>

                <button type="submit" class="btn-guardar" id="btn-login-submit">
                    Entrar al Sistema
                </button>
            </form>

            <div class="links-container">
                <p class="volver-link">
                    <a href="onepage.php">← Volver a la página principal</a>
                </p>
                
                <p class="volver-link" >
                    <a href="register.php" class="link-a-registrar">Registrarme →</a>
                </p>
            </div>
        </div>
    </div>
    <script src="js/validaciones.js?v=3"></script>
</body>
</html>