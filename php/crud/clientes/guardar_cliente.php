<?php
include '../../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni    = $_POST['dni'];
    $nombre  = $_POST['nombre'];
    $email     = $_POST['email'];

    try {
        $sql = "INSERT INTO clientes (dni, nombre, email) 
                VALUES (:dni, :nombre, :email)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'dni'    => $dni,
            'nombre'  => $nombre,
            'email'     => $email,
        ]);
        header("Location: clientes.php?mensaje=registrado");
        exit();

    } catch (PDOException $e) {
        echo "Error al guardar el cliente: " . $e->getMessage();
    }
}
?>