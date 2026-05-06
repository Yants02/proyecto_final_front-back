<?php
include '../../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre    = $_POST['nombre'];
    $telefono  = $_POST['telefono'];
    $email     = $_POST['email'];
    $direccion = $_POST['direccion'];

    try {
        $sql = "INSERT INTO proveedores (nombre, telefono, email, direccion) 
                VALUES (:nombre, :telefono, :email, :direccion)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'nombre'    => $nombre,
            'telefono'  => $telefono,
            'email'     => $email,
            'direccion' => $direccion
        ]);
        header("Location: proveedores.php?mensaje=registrado");
        exit();

    } catch (PDOException $e) {
        echo "Error al guardar el proveedor: " . $e->getMessage();
    }
}
?>