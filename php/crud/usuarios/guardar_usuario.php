<?php
   
    include '../../config/conexion.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $pass = $_POST['password'];
        $pass2 = $_POST['password2'];
        $rol = isset($_POST['rol']) ? $_POST['rol'] : '';

        // Validación campos vacíos)
        if (empty($nombre) || empty($email) || empty($pass) || empty($pass2) || empty($rol)) {
            header("Location: ../../../register.php?error=campos_vacios");
            exit();
        }

        // Validación contraseñas
        if ($pass !== $pass2) {
            header("Location: ../../../register.php?error=password_mismatch");
            exit();
        }

        try {
            // Verificar email
            $sql_check = "SELECT id FROM usuarios WHERE email = :email LIMIT 1";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->execute([':email' => $email]);
            
            if ($stmt_check->fetch()) {
                header("Location: ../../../register.php?error=email_duplicado");
                exit();
            }

            // Guardar usuario
            $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol);";
            $stmt = $conn->prepare($sql);

            $pass_hashed = password_hash($pass, PASSWORD_BCRYPT);

            $stmt->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':password' => $pass_hashed,
                ':rol' => $rol
            ]);

            header("Location: ../../../login.php?success=registrado");
            exit();

        } catch (PDOException $e) {
            echo "Error al guardar el usuario: " . $e->getMessage();
        }
    } else {
        header("Location: ../../../register.php");
        exit();
    }
?>