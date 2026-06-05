<?php
    // Incluimos la conexión usando la misma ruta que en tu login
    include '../config/conexion.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Limpia espacios en blanco de los extremos
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Doble seguridad: Validación por si se saltan el JavaScript del navegador
        if (empty($nombre) || empty($email) || empty($password)) {
            header("Location: ../../register.php?error=campos_vacios");
            exit();
        }

        try {
            // Verificar si el correo electrónico ya está registrado
            $sql_check = "SELECT id FROM usuarios WHERE email = :email LIMIT 1";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->execute([':email' => $email]);
            $usuario_existe = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($usuario_existe) {
                // Si el email ya existe, volvemos al registro con el error que detecta tu JS
                header("Location: ../../register.php?error=email_duplicado");
                exit();
            }

            // Encriptar la contraseña
            // Usamos PASSWORD_DEFAULT, que genera un hash seguro compatible con tu password_verify()
            $password_encriptada = password_hash($password, PASSWORD_DEFAULT);

            // Insertar el nuevo usuario en la base de datos
            // Nota: Si la columna 'rol' tiene un valor por defecto en la BD (ej. 'vendedor' o 'usuario'),
            // podés quitarlo del INSERT. Si no, acá le asigna uno por defecto.
            $sql_insert = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, 'vendedor')";
            $stmt_insert = $conn->prepare($sql_insert);
            
            $resultado = $stmt_insert->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':password' => $password_encriptada
            ]);

            if ($resultado) {
                // Registro exitoso: manda al login con el parámetro success 
                header("Location: ../../login.php?success=registrado");
                exit();
            } else {
                header("Location: ../../register.php?error=error_insercion");
                exit();
            }

        } catch (PDOException $e) {
            echo "Error en el sistema: " . $e->getMessage();
        }
    } else {
        // Si intentan acceder al archivo escribiendo la URL directamente, lo envia directo al registro
        header("Location: ../../register.php");
        exit();
    }
?>