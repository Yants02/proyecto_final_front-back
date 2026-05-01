<?php
    include '../conexion.php';

    try {
        $query = "SELECT id, nombre, email, rol, created_at FROM usuarios ORDER BY id DESC;";
        $resultado = $conn -> query($query);
    } catch (PDOException $e) {
        echo "Error al consultar usuarios: " . $e -> getMessage();
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Usuarios</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Sistema Gestion 2026</div>
        <ul class="nav-links">
            <li><a href="../../index.php">Inicio</a></li>
            <li><a href="productos.php">Productos</a></li>
            <li><a href="#">Proveedores</a></li>
            <li><a href="#">Clientes</a></li>
        </ul>
    </nav>

    <main class="contenedor-principal">
        <aside class="sidebar">
            <h3>Funciones</h3>
            <ul>
                <li><a href="#">Nuevo Usuario</a></li>
                <li><a href="#">Ver Usuarios</a></li>
                
            </ul>
        </aside>

        <section class="contenido">
            <article id="formulario-alta">

                <h2>Cargar Nuevo Usuario</h2>
                <form action="guardar_usuario.php" method="post" class="formulario">
                    
                    <div class="campo">
                        <label for="username">Nombre del Usuario:</label>
                        <input type="text" name="username" id="username" placeholder="Juan Yotti" required>
                    </div>
                    <div class="campo">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" placeholder="jy02@gmail.com" required>
                    </div>
                    <div class="campo">
                        <label for="pass">Contraseña:</label>
                        <input type="password" name="pass" id="pass" placeholder="••••••••" required>
                    </div>
                    <div class="campo">
                        <label for="rol">Rol:</label>
                        <select name="rol" id="id">
                            <option value="" disabled selected>Seleccionar Rol</option>
                            <option value="administrador">Administrador</option>
                            <option value="ventas">Personal de Ventas</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-guardar">Guardar Usuario</button>
                </form>
            </article>

            <hr>

            <article id="formulario-baja">

                <h2>Eliminar Usuario</h2>
                <form action="eliminar_usuario.php" method="post" class="formulario">
                    
                    <div class="campo">
                        <label for="id">ID de Usuario:</label>
                        <input type="number" name="id" id="id" placeholder="23" required>
                    </div>
                    
                    <button type="submit" class="btn-eliminar">Eliminar Usuario</button>
                </form>
            </article>

            <hr>

            <article id="formulario-editar">

                <h2>Editar Usuario</h2>
                <form action="actualizar_usuario.php" method="post" class="formulario">
                    
                    <div class="campo">
                        <label for="id">ID de Usuario:</label>
                        <input type="number" name="id" id="id" placeholder="23" required>
                    </div>
                    <div class="campo">
                        <label for="nuevo-nombre">Nuevo Nombre:</label>
                        <input type="text" name="nuevo-nombre" id="nuevo-nombre" placeholder="Juan Yotti" >
                    </div>
                    <div class="campo">
                        <label for="nuevo-email">Nuevo Email:</label>
                        <input type="email" name="nuevo-email" id="nuevo-email" placeholder="jy2002@gmail.com" >
                    </div>
                    <div class="campo">
                        <label for="nueva-pass">Nueva Contraseña:</label>
                        <input type="password" name="nueva-pass" id="nueva-pass" placeholder="••••••••" >
                    </div>
                    <div class="campo">
                        <label for="nuevo-rol">Nuevo Rol:</label>
                        <select name="nuevo-rol" id="nuevo-rol">
                            <option value="" disabled selected>Seleccionar rol</option>
                            <option value="administrador">Administrador</option>
                            <option value="ventas">Personal de Ventas</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-guardar">Actualizar Usuario</button>
                </form>
            </article>

            <article id="tabla-listado">
                <h2>Usuarios Registrados</h2>
                <table class="tabla-datos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Fecha de Alta</th>
                            <th>Funciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado->fetch()):?>

                            <tr>
                                <td><?php echo $row['id'];?></td>
                                <td><?php echo $row['nombre'];?></td>
                                <td><?php echo $row['email'];?></td>
                                <td><?php echo ucfirst($row['rol']);?></td>
                                <td><?php echo $row['created_at']; ?></td>
                                <td>
                                    <button class="btn-editar">Editar</button>
                                    <button class="btn-borrar">X</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                    </tbody>
                </table>
            </article>
        </section>
    </main>

    <footer class="footer">
        <p>2026 - Panel de Control de Usuarios</p>
    </footer>

    <script src="../../js//validaciones.js"></script>
</body>
</html>