<?php
session_start();
include '../../config/conexion.php';
include __DIR__ . '/../../config/verificar_administrador.php';

// Validamos si llega el ID (Aceptamos tanto POST como GET por compatibilidad con tu formulario)
$id_compra = $_POST['id'] ?? $_GET['id'] ?? null;

if (!$id_compra || empty($id_compra)) {
    header("Location: compras.php");
    exit();
}

try {
    // Iniciamos la transacción para asegurar que la resta de stock y el borrado vayan de la mano
    $conn->beginTransaction();

    // 1. Buscar los productos y cantidades que componen esta compra antes de borrarla
    $sql_buscar = "SELECT id_producto, cantidad FROM detalle_compras WHERE id_compra = :id";
    $stmt_buscar = $conn->prepare($sql_buscar);
    $stmt_buscar->execute(['id' => $id_compra]);
    $items_comprados = $stmt_buscar->fetchAll(PDO::FETCH_ASSOC);

    // Consultar y actualizar el stock
    $sql_consultar_stock = "SELECT stock FROM productos WHERE id = ?";
    $stmt_consultar = $conn->prepare($sql_consultar_stock);

    $sql_ajustar_stock = "UPDATE productos SET stock = ? WHERE id = ?";
    $stmt_ajustar = $conn->prepare($sql_ajustar_stock);

    // 2. Recorrer los productos para aplicar la resta controlada
    foreach ($items_comprados as $item) {
        // Ignoramos si el id_producto es NULL (por si el producto ya fue eliminado del catálogo)
        if (!is_null($item['id_producto'])) {
            
            // Consultamos el stock actual que tiene el producto en la BD
            $stmt_consultar->execute([$item['id_producto']]);
            $stock_actual = $stmt_consultar->fetchColumn();

            // Lógica clave: Restamos, pero si el resultado es menor a 0, lo forzamos a 0
            $nuevo_stock = $stock_actual - $item['cantidad'];
            if ($nuevo_stock < 0) {
                $nuevo_stock = 0;
            }

            // Impactamos el nuevo stock en el producto
            $stmt_ajustar->execute([$nuevo_stock, $item['id_producto']]);
        }
    }

    // 3. Eliminamos la orden principal de compras
    $sql_compra = "DELETE FROM compras WHERE id = :id";
    $stmt_compra = $conn->prepare($sql_compra);
    $stmt_compra->execute(['id' => $id_compra]);

    // Confirmamos la transacción
    $conn->commit();

    // Redirigimos al listado con el mensaje de éxito
    header("Location: compras.php?mensaje=eliminado");
    exit();

} catch (PDOException $e) {
    // Si algo falla, revertimos el stock para no corromper el inventario
    $conn->rollBack();
    die("Error crítico al intentar eliminar la orden de compra: " . $e->getMessage());
}
?>