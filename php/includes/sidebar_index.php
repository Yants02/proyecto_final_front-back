<aside class="sidebar">
    <h3>Módulos del Sistema</h3>
    <ul>
        <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
            <li><a href="php/crud/clientes/clientes.php">Gestion de Clientes</a></li>
            <li><a href="php/crud/productos/productos.php">Gestion de Productos</a></li>
            <li><a href="php/crud/proveedores/proveedores.php">Gestion de Proveedores</a></li> 
            <li><a href="php/crud/compras/compras.php">Gestión de Compras</a></li> 
        <?php endif; ?>
        
            <li><a href="php/crud/ventas/ventas.php">Gestión de Ventas</a></li> 
        <br>
    </ul>
</aside>