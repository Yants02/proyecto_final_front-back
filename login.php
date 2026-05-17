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
            <form action="index.php" method="GET" class="formulario">
                <div class="campo">
                    <label>Correo Electrónico:</label>
                    <input type="email" name="email" placeholder="ejemplo@correo.com">
                </div>

                <div class="campo">
                    <label>Contraseña:</label>
                    <input type="password" name="password" placeholder="••••••••">
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
</body>
</html>