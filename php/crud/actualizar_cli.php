<?php
    include("../conexion.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $camposAActualizar = array();
        $valores = array();

        if (!empty($_POST['nuevo-dni'])){
            $camposAActualizar[] = "dni = ?";
            $valores[] = $_POST['nuevo-dni'];
        }

        if (!empty($_POST['nuevo-nombre'])){
            $camposAActualizar[] = "nombre = ?";
            $valores[] = $_POST['nuevo-nombre'];
        }

        if (!empty($_POST['nuevo-email'])){
            $camposAActualizar[] = "email = ?";
            $valores[] = $_POST['nuevo-email'];
        }

        if (count($camposAActualizar) > 0) {
            try {
                $datos = implode(", ", $camposAActualizar);

                $sql = "UPDATE clientes SET $datos WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $valores[] = $id;
                $stmt->execute($valores);
                header("Location: clientes.php?mensaje=actualizado");
                exit();

            } catch (PDOException $e) {
                echo "Error al actualizar: " . $e->getMessage();
            }
        } else {
            header("Location: clientes.php?mensaje=no_hay_datos");
            exit();
        } 
    }
?>