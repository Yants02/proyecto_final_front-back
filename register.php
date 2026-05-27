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
                <h3>Registrarse</h3>
            </div>
            <form action="php/crud/usuarios/guardar_usuario.php" method="POST" class="formulario" id="formRegister" novalidate>
                <div class="campo">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Juan Andres Yotti">
                </div>
                <div class="campo">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" name="email" id="email" placeholder="ejemplo@correo.com">
                </div>

                <div class="campo">
                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" id="password" placeholder="••••••••">
                </div>
                <div class="campo">
                    <label for="password2">Reingresar Contraseña:</label>
                    <input type="password" name="password2" id="password2" placeholder="••••••••">
                </div>
                
                <div class="campo">
                    <label></label>
                    <select name="rol" id="rol">
                        <option value="elige un rol" disabled>Elige un rol</option>
                        <option value="administrador">Administrador</option>
                        <option value="ventas">Ventas</option>
                    </select>
                </div>

                <button type="submit" class="btn-guardar" id="btn-login-submit">
                    Registrarme
                </button>
            </form>
            <div class="links-container">
                <p class="volver-link">
                    <a href="onepage.php">← Volver a la página principal</a>
                </p>
                <p class="volver-link">
                    <a href="login.php">Iniciar Sesión →</a>
                </p>
            </div>
            
        </div>
    </div>
<script src="js/validaciones.js?v=3"></script>
</body>
</html>