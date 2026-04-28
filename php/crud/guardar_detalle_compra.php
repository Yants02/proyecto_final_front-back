<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_compra   = $_POST['id_compra'];
    $id_producto = $_POST['id_producto'];
    $cantidad    = $_POST['cantidad'];
    $precio      = $_POST['precio_costo'];

    try {
        // TRANSACCIÓN 
        $conn->beginTransaction();

        // BUSCAR NOMBRE DEL PRODUCTO (Respaldo)
        $stmt_prod = $conn->prepare("SELECT nombre FROM productos WHERE id = :id");
        $stmt_prod->execute(['id' => $id_producto]);
        $producto = $stmt_prod->fetch(PDO::FETCH_ASSOC);
        $nombre_respaldo = $producto['nombre'];

        // INSERTAR EL DETALLE
        $sql_det = "INSERT INTO detalle_compras (id_compra, id_producto, nombre_producto, cantidad, precio_costo) 
                    VALUES (:id_c, :id_p, :nom, :cant, :pre)";
        $stmt_det = $conn->prepare($sql_det);
        $stmt_det->execute([
            'id_c'  => $id_compra,
            'id_p'  => $id_producto,
            'nom'   => $nombre_respaldo,
            'cant'  => $cantidad,
            'pre'   => $precio
        ]);

        // ACTUALIZAR EL STOCK DEL PRODUCTO
        $sql_stock = "UPDATE productos SET stock = stock + :cant WHERE id = :id";
        $stmt_stock = $conn->prepare($sql_stock);
        $stmt_stock->execute([
            'cant' => $cantidad,
            'id'   => $id_producto
        ]);

        $conn->commit();

        header("Location: ver_detalle_compra.php?id=" . $id_compra . "&msj=agregado");
        exit();
    } catch (PDOException $e) {
        $conn->rollBack(); 
        echo "Error: " . $e->getMessage();
    }
}
