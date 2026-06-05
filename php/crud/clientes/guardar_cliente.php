<?php

include __DIR__ . '/../../config/verificar_administrador.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    die("Acceso denegado.");
}


include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $usuario_id = $_SESSION['usuario_id'];

    try {
        $sql = "INSERT INTO clientes (dni, nombre, email, usuario_id) VALUES (:dni, :nombre, :email, :usuario_id)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':dni' => $dni, 
            ':nombre' => $nombre, 
            ':email' => $email,
            ':usuario_id' => $usuario_id
        ]);

        header("Location: clientes.php?mensaje=creado");
        exit();

    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            header("Location: clientes.php?error=dni_duplicado");
            exit();
        } else {
            die("Error crítico en la base de datos: " . $e->getMessage());
        }
    }
}
?>