<?php
session_start();
include '../../config/conexion.php';
include __DIR__ . '/../../config/verificar_administrador.php';

// Validar ID
$id_compra = $_POST['id'] ?? $_GET['id'] ?? null;

if (!$id_compra || empty($id_compra)) {
    header("Location: compras.php");
    exit();
}

try {
    // Borrar compra y restar stock
    $conn->beginTransaction();

    $sql_buscar = "SELECT id_producto, cantidad FROM detalle_compras WHERE id_compra = :id";
    $stmt_buscar = $conn->prepare($sql_buscar);
    $stmt_buscar->execute(['id' => $id_compra]);
    $items_comprados = $stmt_buscar->fetchAll(PDO::FETCH_ASSOC);

    $sql_consultar_stock = "SELECT stock FROM productos WHERE id = ?";
    $stmt_consultar = $conn->prepare($sql_consultar_stock);

    $sql_ajustar_stock = "UPDATE productos SET stock = ? WHERE id = ?";
    $stmt_ajustar = $conn->prepare($sql_ajustar_stock);

    foreach ($items_comprados as $item) {
        if (!is_null($item['id_producto'])) {
            
            $stmt_consultar->execute([$item['id_producto']]);
            $stock_actual = $stmt_consultar->fetchColumn();

            $nuevo_stock = $stock_actual - $item['cantidad'];
            if ($nuevo_stock < 0) {
                $nuevo_stock = 0;
            }

            $stmt_ajustar->execute([$nuevo_stock, $item['id_producto']]);
        }
    }

    $sql_compra = "DELETE FROM compras WHERE id = :id";
    $stmt_compra = $conn->prepare($sql_compra);
    $stmt_compra->execute(['id' => $id_compra]);

    $conn->commit();

    header("Location: compras.php?mensaje=eliminado");
    exit();

} catch (PDOException $e) {
    $conn->rollBack();
    die("Error crítico al intentar eliminar la orden de compra: " . $e->getMessage());
}
?>