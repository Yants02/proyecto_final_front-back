<?php
    include '../conexion.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $pass = $_POST['pass'];
        $email = $_POST['email'];
        $rol = $_POST['rol'];
        
        try {
            $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol);";

            $stmt = $conn -> prepare($sql);

            $pass_hashed = password_hash($pass, PASSWORD_BCRYPT);

            $stmt -> execute([
                ':nombre' => $username,
                ':email' => $email,
                ':password' => $pass_hashed,
                ':rol' => $rol
            ]);

            header("Location: usuarios.php?mensaje=registrado");

        } catch (PDOException $e) {
            echo "Error al guardar el usuario: " . $e -> getMessage();
        }
    }
?>