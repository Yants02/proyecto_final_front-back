<?php
session_start();
include '../../conexion.php';

if (!isset($_SESSION['compra_temp']) || empty($_SESSION['compra_temp']['productos'])) {
    header("Location: compras.php");
    exit();
}

try {
    $conn->beginTransaction();

    // Crear compra
    $id_prov = $_SESSION['compra_temp']['id_prov'];
    $stmt_p = $conn->prepare("SELECT nombre FROM proveedores WHERE id = ?");
    $stmt_p->execute([$id_prov]);
    $nom_prov = $stmt_p->fetchColumn();

    $ins_c = $conn->prepare("INSERT INTO compras (id_proveedor, nombre_proveedor, total) VALUES (?, ?, 0)");
    $ins_c->execute([$id_prov, $nom_prov]);
    $id_compra = $conn->lastInsertId();

    $total_final = 0;

    // Iterar todos los productos de la sesión
    foreach ($_SESSION['compra_temp']['productos'] as $item) {
        $subtotal = $item['cantidad'] * $item['precio_costo'];
        $total_final += $subtotal;

        // Insertar detalle
        $ins_d = $conn->prepare("INSERT INTO detalle_compras (id_compra, id_producto, nombre_producto, cantidad, precio_costo) VALUES (?, ?, ?, ?, ?)");
        $ins_d->execute([$id_compra, $item['id_prod'], $item['nombre_producto'], $item['cantidad'], $item['precio_costo']]);

        // Actualizar stock
        $upd_s = $conn->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
        $upd_s->execute([$item['cantidad'], $item['id_prod']]);
    }

    // Actualizar el total de la compra
    $upd_t = $conn->prepare("UPDATE compras SET total = ? WHERE id = ?");
    $upd_t->execute([$total_final, $id_compra]);

    // Guardar todo
    $conn->commit();
    
    // Limpiar la sesión 
    unset($_SESSION['compra_temp']);
    header("Location: compras.php?msj=exito");

} catch (Exception $e) {
    $conn->rollBack();
    echo "Error en la transacción: " . $e->getMessage();
}