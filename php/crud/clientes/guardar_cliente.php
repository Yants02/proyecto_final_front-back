<?php
session_start();
include '../../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturás los datos (asegurate de que los names coincidan con tu form)
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    try {
        $sql = "INSERT INTO clientes (dni, nombre, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$dni, $nombre, $email]);

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