<!-- <?php
    // include '../../config/conexion.php';

    // if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //     $nombre = $_POST['nombre'];
    //     $pass = $_POST['password'];
    //     $email = $_POST['email'];
    //     $rol = $_POST['rol'];
        
    //     try {
    //         $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol);";

    //         $stmt = $conn -> prepare($sql);

    //         $pass_hashed = password_hash($pass, PASSWORD_BCRYPT);

    //         $stmt -> execute([
    //             ':nombre' => $nombre,
    //             ':email' => $email,
    //             ':password' => $pass_hashed,
    //             ':rol' => $rol
    //         ]);

    //         header("Location: ../../../index.php?mensaje=registrado");
    //         exit();

    //     } catch (PDOException $e) {
    //         echo "Error al guardar el usuario: " . $e -> getMessage();
    //     }
    // }
?> -->

<?php
    include __DIR__ . '/../../config/verificar_administrador.php';
    include '../../config/conexion.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Limpiamos los textos
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $pass = $_POST['password'];
        $pass2 = $_POST['password2'];
        $rol = isset($_POST['rol']) ? $_POST['rol'] : '';

        // 1. Validación de seguridad en el servidor (Campos vacíos)
        if (empty($nombre) || empty($email) || empty($pass) || empty($pass2) || empty($rol)) {
            header("Location: ../../../register.php?error=campos_vacios");
            exit();
        }

        // 2. Validación de seguridad en el servidor (Contraseñas idénticas)
        if ($pass !== $pass2) {
            header("Location: ../../../register.php?error=password_mismatch");
            exit();
        }

        try {
            // 3. Verificar si el email ya existe para no romper el índice UNIQUE de la BD
            $sql_check = "SELECT id FROM usuarios WHERE email = :email LIMIT 1";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->execute([':email' => $email]);
            
            if ($stmt_check->fetch()) {
                header("Location: ../../../register.php?error=email_duplicado");
                exit();
            }

            // 4. Si todo está ok, guardamos
            $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol);";
            $stmt = $conn->prepare($sql);

            $pass_hashed = password_hash($pass, PASSWORD_BCRYPT);

            $stmt->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':password' => $pass_hashed,
                ':rol' => $rol
            ]);

            // Redirección ideal: Lo mandamos a login.php avisando que ya se puede loguear
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