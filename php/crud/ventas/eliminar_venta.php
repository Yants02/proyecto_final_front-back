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

    // 1. PASO NUEVO: Buscar los productos y cantidades que se cargaron en esta venta
    $sql_buscar = "SELECT id_producto, cantidad FROM detalle_ventas WHERE id_venta = :id";
    $stmt_buscar = $conn->prepare($sql_buscar);
    $stmt_buscar->execute(['id' => $id_venta]);
    $items_vendidos = $stmt_buscar->fetchAll(PDO::FETCH_ASSOC);

    // 2. PASO NUEVO: Recorrer cada ítem y DEVOLVER las unidades al stock del catálogo
    $sql_reintegrar = "UPDATE productos SET stock = stock + ? WHERE id = ?";
    $stmt_reintegrar = $conn->prepare($sql_reintegrar);

    foreach ($items_vendidos as $item) {
        // Validamos que el id_producto no sea NULL (por si borraste el producto del catálogo antes)
        if (!is_null($item['id_producto'])) {
            $stmt_reintegrar->execute([
                $item['cantidad'],
                $item['id_producto']
            ]);
        }
    }

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
