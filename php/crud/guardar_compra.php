<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_proveedor = $_POST['id_proveedor'];

    try {
        $sql_nom = "SELECT nombre FROM proveedores WHERE id = :id";
        $stmt_nom = $conn->prepare($sql_nom);
        $stmt_nom->execute(['id' => $id_proveedor]);
        $proveedor = $stmt_nom->fetch(PDO::FETCH_ASSOC);

        $nombre_respaldo = $proveedor['nombre'];

        $sql = "INSERT INTO compras (id_proveedor, nombre_proveedor) 
                VALUES (:id_prov, :nom_respaldo)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'id_prov'      => $id_proveedor,
            'nom_respaldo' => $nombre_respaldo
        ]);

        $id_compra_nueva = $conn->lastInsertId();

        header("Location: compras.php?id_compra=" . $id_compra_nueva);
        exit();

    } catch (PDOException $e) {
        echo "Error al registrar compra: " . $e->getMessage();
    }
}
?>