<?php
$ruta = isset($ruta_base) ? $ruta_base : ''; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - Kiosko Manager</title>
<link rel="stylesheet" href="<?php echo $ruta; ?>css/header.css?v=2">
    <link rel="stylesheet" href="<?php echo $ruta; ?>css/estilos.css?v=2">
    
    <?php if($ruta != ''): ?>
        <link rel="stylesheet" href="<?php echo $ruta; ?>css/estilos_cliente.css">
    <?php endif; ?>
</head>
<body>
    <header class="header">
        <a href="<?php echo $ruta; ?>index.php" class="logo">
            <img src="<?php echo $ruta; ?>img/icono-tienda.svg" alt="KioskoManager">
            <span class="logo-text">Kiosko Manager</span>
        </a>
        
        <nav>
            <ul>
                <a href="<?php echo $ruta; ?>onepage.php" id="btn-login">Volver a Inicio</a>
            </ul>
        </nav>
    </header>