<?php
session_start();
include '../../config/conexion.php';

// Si no llega el ID, lo mandamos de vuelta
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: venta.php");
    exit();
}

$id_venta = $_GET['id'];

try {
    // Iniciamos la transacción por seguridad
    $conn->beginTransaction();

    // 1. Primero eliminamos todos los ítems de esta compra en el detalle
    $sql_detalles = "DELETE FROM detalle_ventas WHERE id_venta = :id";
    $stmt_detalles = $conn->prepare($sql_detalles);
    $stmt_detalles->execute(['id' => $id_venta]);

    // 2. Ahora sí, eliminamos la orden principal de compras
    $sql_venta = "DELETE FROM ventas WHERE id = :id";
    $stmt_venta = $conn->prepare($sql_venta);
    $stmt_venta->execute(['id' => $id_venta]);

    // Confirmamos los cambios
    $conn->commit();

    // Redirigimos al listado con un mensaje de éxito (podés usar este mensaje en tu compras.php si querés)
    header("Location: ventas.php?mensaje=eliminado");
    exit();

} catch (PDOException $e) {
    // Si algo sale mal, revertimos todo
    $conn->rollBack();
    die("Error crítico al intentar eliminar la orden: " . $e->getMessage());
}
?>