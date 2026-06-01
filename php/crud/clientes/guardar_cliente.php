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
    // Capturás los datos (asegurate de que los names coincidan con tu form)
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

        // Si salió bien, volvemos con mensaje de éxito
        header("Location: clientes.php?mensaje=creado");
        exit();

    } catch (PDOException $e) {
        // Acá viene la magia: Si el error es 1062 (Duplicate entry)
        if ($e->errorInfo[1] == 1062) {
            // Lo mandamos de vuelta pero avisando que hay un error
            header("Location: clientes.php?error=dni_duplicado");
            exit();
        } else {
            // Si es otro error distinto, lo frena y avisa
            die("Error crítico en la base de datos: " . $e->getMessage());
        }
    }
}
?>