<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    die("Acceso denegado.");
}


include '../../config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre    = $_POST['nombre'];
    $telefono  = $_POST['telefono'];
    $email     = $_POST['email'];
    $direccion = $_POST['direccion'];
    $usuario_id = $_SESSION['usuario_id'];

    try {
        $sql = "INSERT INTO proveedores (nombre, telefono, email, direccion, usuario_id) VALUES (:nombre, :telefono, :email, :direccion, :usuario_id)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':nombre'    => $nombre,
            ':telefono'  => $telefono,
            ':email'     => $email,
            ':direccion' => $direccion,
            ':usuario_id' => $usuario_id
        ]);
        header("Location: proveedores.php?mensaje=registrado");
        exit();

    } catch (PDOException $e) {
        echo "Error al guardar el proveedor: " . $e->getMessage();
    }
}
?>