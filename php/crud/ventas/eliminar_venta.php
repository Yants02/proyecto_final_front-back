<?php
session_start();
include '../../config/conexion.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: venta.php");
    exit();
}

$id_venta = $_GET['id'];

try {
    $conn->beginTransaction();

    $sql_buscar = "SELECT id_producto, cantidad FROM detalle_ventas WHERE id_venta = :id";
    $stmt_buscar = $conn->prepare($sql_buscar);
    $stmt_buscar->execute(['id' => $id_venta]);
    $items_vendidos = $stmt_buscar->fetchAll(PDO::FETCH_ASSOC);

    $sql_reintegrar = "UPDATE productos SET stock = stock + ? WHERE id = ?";
    $stmt_reintegrar = $conn->prepare($sql_reintegrar);

    foreach ($items_vendidos as $item) {
        if (!is_null($item['id_producto'])) {
            $stmt_reintegrar->execute([
                $item['cantidad'],
                $item['id_producto']
            ]);
        }
    }

    $sql_venta = "DELETE FROM ventas WHERE id = :id";
    $stmt_venta = $conn->prepare($sql_venta);
    $stmt_venta->execute(['id' => $id_venta]);

    $conn->commit();

    header("Location: ventas.php?mensaje=eliminado");
    exit();
} catch (PDOException $e) {
    $conn->rollBack();
    die("Error crítico al intentar eliminar la orden: " . $e->getMessage());
}
