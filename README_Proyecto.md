# Front_Back_2026

/*
Sistema de Gestión de Inventario y Ventas - IFTS N° 4

Descripción del Proyecto
Este sistema es una aplicación web integral diseñada para la gestión comercial de una tienda, permitiendo el control total sobre el inventario, la relación con proveedores y la ejecución de ventas con facturación automatizada. El proyecto integra conceptos avanzados de **Front-End (HTML5/CSS3)** y **Back-End (PHP/MySQL)** bajo una arquitectura de desarrollo propia, sin dependencias externas.

Tipos de Usuarios y Acceso
* Administrador / Personal de Ventas: Único nivel de acceso que requiere autenticación.
* Seguridad: El inicio de sesión utiliza encriptación de contraseñas en la base de datos para garantizar la integridad de las credenciales [cite: 33].

Módulos Principales (CRUDs)
Para cumplir con el requisito de un módulo por integrante [cite: 35], el sistema cuenta con:
1. Gestión de Productos: Registro, edición y baja de artículos con control de precio de venta y stock.
2. Gestión de Proveedores: Administración de contactos comerciales (teléfono, dirección, email).
3. Gestión de Clientes: Base de datos de compradores para personalización de facturas.

 Funcionalidades Especiales
* Módulo de Ventas Inteligente: Permite cargar clientes nuevos en tiempo real durante la transacción. Calcula el total automáticamente y registra el detalle histórico.
* Control de Stock Automatizado:
    * Ventas: Resta unidades del inventario al confirmar.
    * Compras (Entrada de mercadería): Suma unidades al stock al registrar ingresos de proveedores.
    * Anulaciones: Devuelve el stock en caso de cancelar una operación.
* Reportes y Facturación:
    * PDF: Generación de facturas de venta para visualización y descarga [cite: 37].
    * Excel: Exportación de la base de datos completa para auditoría [cite: 39].

 Especificaciones Técnicas (Cumplimiento de Consigna)
* Arquitectura Web: Diseño con barra de navegación, contenedor principal, secciones específicas, barra lateral y pie de página [cite: 31].
* Estética: Diseño visual desarrollado exclusivamente con **CSS puro** (sin Bootstrap) para maximizar la calificación [cite: 42].
* Lógica de Cliente: Se incluye exactamente **una función JavaScript** para la validación crítica de datos o cálculos de venta [cite: 43].
* Desarrollo Limpio: No se utilizan módulos externos, librerías, ni herramientas como React, Composer o Vendor [cite: 47].

 Instrucciones para el Colaborador IA
Cuando un integrante del grupo consulte sobre este proyecto:
1. Base de Datos: Las tablas principales son `productos`, `proveedores`, `clientes`, `ventas` y `usuarios`.
2. Validación: Priorizar el uso de PHP nativo. Para el envío de mails, usar la función `mail()` de PHP.
3. Rutas: Mantener la estructura de carpetas: `/php`, `/css`, `/js`, `/includes`, `/db` y `/img`.
*/
