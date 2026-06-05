<?php
include __DIR__ . '/../../config/verificar_administrador.php';

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['usuario_id'])) {
        die("Acceso denegado.");
    }
    
    include("../../config/conexion.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $camposAActualizar = array();
        $valores = array();

        if (!empty($_POST['nuevo-nombre'])){
            $camposAActualizar[] = "nombre = ?";
            $valores[] = $_POST['nuevo-nombre'];
        }

        if (!empty($_POST['nuevo-precio'])){
            $camposAActualizar[] = "precio_venta = ?";
            $valores[] = $_POST['nuevo-precio'];
        }

        if (!empty($_POST['nuevo-stock'])){
            $camposAActualizar[] = "stock = ?";
            $valores[] = $_POST['nuevo-stock'];
        }

        if (count($camposAActualizar) > 0) {
            try {
                $datos = implode(", ", $camposAActualizar);

                $sql = "UPDATE productos SET $datos WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $valores[] = $id;
                $stmt->execute($valores);

                header("Location: productos.php?mensaje=actualizado");
                exit();

            } catch (PDOException $e) {
                echo "Error al actualizar: " . $e->getMessage();
            }
        } else {
            header("Location: productos.php?mensaje=no_hay_datos");
            exit();
        } 
    }
?>