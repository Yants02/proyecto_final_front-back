<?php
    include("../conexion.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = mysqli_real_escape_string($conn, $POST['id']);

        // array para que solamente se modifique el campo con datos nuevos
        $camposAActualizar = array();
        // Si los campos no estan vacios se agregan al array para actualizarse
        
        if (!empty($_POST['nuevo-nombre'])){
            $nombre = mysqli_real_escape_string($conn, $POST['nuevo-nombre']);
            $camposAActualizar[] = "nombre = '$nombre'";
        }

        if (!empty($_POST['nuevo-precio'])){
            $precio = mysqli_real_escape_string($conn, $POST['nuevo-precio']);
            $camposAActualizar[] = "precio_venta = '$precio'";
        }

        if (!empty($_POST['nuevo-stock'])){
            $stock = mysqli_real_escape_string($conn, $POST['nuevo-stock']);
            $camposAActualizar[] = "stock = '$stock'";
        }

        if (count($camposAActualizar) > 0) {
            // implode() => agrupa los elementos de un array en un string usando un separador ",". 
            // Evita errores de sintaxis en la query
            $datos = implode(", ", $camposAActualizar);

            $sql = "UPDATE productos SET $datos WHERE id = '$id';";

            if (mysqli_query($conn, $sql)) {
                if (mysqli_affected_rows($conn) > 0) {
                    header("Location: productos.php?mensaje=actualizado");
                } else {
                    header("Location: productos.php?mensaje=sin_cambios");
                }
                exit();
            }
            else {
                echo "Error al actualizar: " . mysqli_error($conn);
            }
        }
        // si no se envia ningun campo a actualizar
        else {
            header("Location: productos.php?mensaje=no_hay_datos");
            exit();
        } 
    }

?>