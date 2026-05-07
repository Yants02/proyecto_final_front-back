<?php
include '../../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_compra   = $_POST['id_compra']; // Puede ser 0
    $id_prov     = $_POST['id_proveedor'];
    $id_producto = $_POST['id_producto'];
    $cantidad    = $_POST['cantidad'];
    $precio      = $_POST['precio_costo'];

    try {
        $conn->beginTransaction();

        // 1. SI LA COMPRA NO EXISTE, LA CREAMOS AQUÍ ADENTRO
        if ($id_compra == 0) {
            $stmt_p = $conn->prepare("SELECT nombre FROM proveedores WHERE id = ?");
            $stmt_p->execute([$id_prov]);
            $nom_prov = $stmt_p->fetchColumn();

            $ins_c = $conn->prepare("INSERT INTO compras (id_proveedor, nombre_proveedor, total) VALUES (?, ?, 0)");
            $ins_c->execute([$id_prov, $nom_prov]);
            $id_compra = $conn->lastInsertId();
        }

        // 2. INSERTAR DETALLE
        $stmt_prod = $conn->prepare("SELECT nombre FROM productos WHERE id = ?");
        $stmt_prod->execute([$id_producto]);
        $nom_prod = $stmt_prod->fetchColumn();

        $stmt_det = $conn->prepare("INSERT INTO detalle_compras (id_compra, id_producto, nombre_producto, cantidad, precio_costo) VALUES (?, ?, ?, ?, ?)");
        $stmt_det->execute([$id_compra, $id_producto, $nom_prod, $cantidad, $precio]);

        // 3. ACTUALIZAR STOCK
        $stmt_stock = $conn->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
        $stmt_stock->execute([$cantidad, $id_producto]);

        // 4. ACTUALIZAR TOTAL CABECERA
        $stmt_total = $conn->prepare("UPDATE compras SET total = (SELECT SUM(cantidad * precio_costo) FROM detalle_compras WHERE id_compra = ?) WHERE id = ?");
        $stmt_total->execute([$id_compra, $id_compra]);

        $conn->commit(); 
        header("Location: ver_detalle_compra.php?id=" . $id_compra);
        exit();

    } catch (PDOException $e) {
        $conn->rollBack();
        echo "Error: No se guardó nada. " . $e->getMessage();
    }
}