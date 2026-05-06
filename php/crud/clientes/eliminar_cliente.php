<?php
include '../../conexion.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        $sql = "DELETE FROM clientes WHERE id = :id";
        $stmt = $conn->prepare($sql);

        $stmt->execute(['id' => $id]);

        header("Location: clientes.php");
        exit();

    } catch (PDOException $e) {
        die("Error al eliminar: " . $e->getMessage());
    }
} else {
    // Si no hay ID, volvemos igual
    header("Location: clientes.php");
    exit();
}