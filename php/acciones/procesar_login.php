<?php
    include '../config/conexion.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        try {
            // Busca al usuario por email
            $sql = "SELECT id, nombre, password, rol FROM usuarios WHERE email = :email LIMIT 1";
            $stmt = $conn -> prepare($sql);
            $stmt -> execute([':email' => $email]);
            $usuario = $stmt -> fetch(PDO::FETCH_ASSOC);

            // Verifica si existe el usuario y si la password coincide con el hash
            if ($usuario && password_verify($password, $usuario['password'])) {

                // Inicia la sesion normal del servidor
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['usuario_id'] = $usuario['id']; 
                $_SESSION['nombre'] = $usuario['nombre']; 
                $_SESSION['rol'] = $usuario['rol']; 
                
                // Control del checkbox y generacion del token para persistencia de la sesion
            
                if (isset($_POST['mantener_sesion_abierta'])) {
                    $token = bin2hex(random_bytes(32));
                    
                    // Guardar el token en la columna sesion_token
                    $sql_token = "UPDATE usuarios SET sesion_token = :token WHERE id = :id";
                    
                    $stmt_token = $conn ->prepare($sql_token);
                    $stmt_token -> execute([':token' => $token, ':id' => $usuario['id']]);
                    
                    // Crea la cookie por 30 dias (86400 segundos * 30)
                    setcookie('rm_token', $token, time() + (86400 * 30), "/", "", false, true);    
                }
                    
                // login exito: lleva al usuario al index
                header("Location: ../../index.php");
                exit();
            } else {
                // login error de credenciales: Vuelve al login con un msj de error.
                header("Location: ../../login.php?error=1");
                exit();
            }

        } catch (PDOException $e) {
            echo "Error en el sistema: " . $e -> getMessage();
        }
    } else {
        // Si se intenta entrar al index directamente por la url, lo envia al login.
        header("Location: ../../login.php");
        exit();
    }
?>