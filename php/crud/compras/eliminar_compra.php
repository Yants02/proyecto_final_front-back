<?php
session_start();
include '../../config/conexion.php';

// Si no llega el ID, lo mandamos de vuelta
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: compras.php");
    exit();
}

$id_compra = $_GET['id'];

try {
    // Iniciamos la transacción por seguridad
    $conn->beginTransaction();

    // 1. Primero eliminamos todos los ítems de esta compra en el detalle
    $sql_detalles = "DELETE FROM detalle_compras WHERE id_compra = :id";
    $stmt_detalles = $conn->prepare($sql_detalles);
    $stmt_detalles->execute(['id' => $id_compra]);

    // 2. Ahora sí, eliminamos la orden principal de compras
    $sql_compra = "DELETE FROM compras WHERE id = :id";
    $stmt_compra = $conn->prepare($sql_compra);
    $stmt_compra->execute(['id' => $id_compra]);

    // Confirmamos los cambios
    $conn->commit();

    // Redirigimos al listado con un mensaje de éxito (podés usar este mensaje en tu compras.php si querés)
    header("Location: compras.php?mensaje=eliminado");
    exit();

} catch (PDOException $e) {
    // Si algo sale mal, revertimos todo
    $conn->rollBack();
    die("Error crítico al intentar eliminar la orden: " . $e->getMessage());
}
?>