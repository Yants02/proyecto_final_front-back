<?php
    include __DIR__ . '/../../config/verificar_administrador.php';

    include("../../config/conexion.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $camposAActualizar = array();
        $valores = array();

        //Construcción dinámica con marcadores
        if (!empty($_POST['nuevo-nombre'])){
            $camposAActualizar[] = "nombre = ?";
            $valores[] = $_POST['nuevo-nombre'];
        }

        if (!empty($_POST['nuevo-email'])){
            $camposAActualizar[] = "email = ?";
            $valores[] = $_POST['nuevo-email'];
        }

        if (!empty($_POST['nueva-pass'])){
            $camposAActualizar[] = "password = ?";
            $valores[] = $_POST['nueva-pass'];
        }

        if (!empty($_POST['nuevo-rol'])) {
            $camposAActualizar[] = "rol = ?";
            $valores[] = $_POST['nuevo-rol'];
        }

        if (count($camposAActualizar) > 0) {
            try {
                //Unimos los campos: 
                $datos = implode(", ", $camposAActualizar);

                $sql = "UPDATE usuarios SET $datos WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $valores[] = $id;
                $stmt->execute($valores);

                // En PDO, rowCount() nos dice si se cambió algo
                // Usamos >= 0 porque si mandan los mismos datos, no cuenta como error
                header("Location: usuarios.php?mensaje=actualizado");
                exit();

            } catch (PDOException $e) {
                echo "Error al actualizar: " . $e->getMessage();
            }
        } else {
            header("Location: usuarios.php?mensaje=no_hay_datos");
            exit();
        } 
    }
?>