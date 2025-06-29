<?php
include_once __DIR__ . '/../src/controllers/UsuarioController.php';
include_once __DIR__ . '/../src/controllers/PedidoController.php';
include_once __DIR__ . '/../src/controllers/ProductoPedidoController.php';
include_once __DIR__ . '/../src/controllers/DatosEnvioController.php';
include_once __DIR__ . '/../src/controllers/MarcaController.php';
include_once __DIR__ . '/../src/controllers/CarritoController.php';
include_once __DIR__ . '/../src/controllers/ProductoController.php';
include_once __DIR__ . '/../src/controllers/CategoriaController.php';

$usuarioController = new UsuarioController();
$pedidoController = new PedidoController();
$productoPedidoController = new ProductoPedidoController();
$datosEnvioController = new DatosEnvioController();
$marcaController = new MarcaController();
$carritoController = new CarritoController();
$productoController = new ProductoController();
$categoriaController = new CategoriaController();


// RUTAS PARA USUARIOS
$router->add('GET', '/usuarios', [$usuarioController, 'getUsuarios']);
$router->add('GET', '/usuarios/{id}', [$usuarioController, 'getUsuarioById']);
$router->add('POST', '/usuarios', [$usuarioController, 'create']);
$router->add('POST', '/inisiarsesion', [$usuarioController, 'iniciarSecion']);
$router->add('PUT', '/recuperarpassword', [$usuarioController, 'RecuperarPassword']);
$router->add('PUT', '/cambiopassword', [$usuarioController, 'CambioPassword']);
$router->add('POST', '/verificotoken', [$usuarioController, 'VerificoToken']);
$router->add('GET', '/historialcompras/{id}', [$usuarioController, 'todastuscompras']);
$router->add('POST', '/desactivarcuenta/{id}', [$usuarioController, 'desactivacuenta']);
$router->add('POST', '/cerrarsesion/{id}', [$usuarioController, 'cerrarsesion']);
$router->add('PUT', '/cambiopassdesdedetalles', [$usuarioController, 'cambiopassdesdeDetalles']);
$router->add('PUT', '/usuarios/hash-passwords', [$usuarioController, 'hashPasswords']);
$router->add('POST', '/enviarContacto', [$usuarioController, 'sendEmailContact']);

//RUTAS USUARIO-ADMINISTRADOR
$router->add('POST', '/buscarUsuarios', [$usuarioController, 'buscarUsuarios']);
$router->add('POST', '/crearGestor', [$usuarioController, 'crearGestor']);
$router->add('PUT', '/modificarGestor', [$usuarioController, 'modificarGestor']);
$router->add('DELETE', '/eliminarGestor', [$usuarioController, 'eliminarGestor']);

// RUTAS PARA PEDIDOS
$router->add('GET', '/pedidos', [$pedidoController, 'getPedidos']);
$router->add('GET', '/pedidos/{id}', [$pedidoController, 'getPedidoById']);
$router->add('GET', '/pedidos/cliente/{id}', [$pedidoController, 'getPedidoByCliente']);
$router->add('POST', '/pedidos', [$pedidoController, 'create']);
$router->add('PATCH', '/pedidos/{id}', [$pedidoController, 'updateStatus']);
$router->add('DELETE', '/pedidos/{id}', [$pedidoController, 'cancel']);
$router->add('POST', '/pedidos/enviarConfirmacionEmail', [$pedidoController, 'sendEmailConfirmation']);
$router->add('POST', '/pedidos/enviarConfirmacionPagoEmail', [$pedidoController, 'sendEmailPaymentConfirmation']);
$router->add('POST', '/pedidos/descargarPDF', [$pedidoController, 'downloadOrderPDF']);

// RUTAS PARA PRODUCTOS PEDIDOS
$router->add('GET', '/productos/pedido/{id_pedido}', [$productoPedidoController, 'getProductoPedidoByIdPedido']);

// RUTAS PARA DATOS DE ENVIO
$router->add('GET', '/pedido/datosenvio', [$datosEnvioController, 'getDatosEnvio']);
$router->add('GET', '/pedido/datosenvio/{id}', [$datosEnvioController, 'getDatosEnvioById']);
$router->add('POST', '/pedido/datosenvio', [$datosEnvioController, 'create']);
$router->add('GET', '/pedido/datosenvio/{id}', [$datosEnvioController, 'getDatosEnvioById']);

// RUTAS PARA MARCAS
$router->add('GET', '/marcas', [$marcaController, 'getMarcas']);
$router->add('POST', '/marcas', [$marcaController, 'create']);

// RUTAS PARA CARRITO
$router->add('GET', '/carrito/{ID_Cliente}', [$carritoController, 'getCarrito']);
$router->add('GET', '/carritoDetallado/{ID_Cliente}', [$carritoController, 'getCarritoDetallado']);
$router->add('GET', '/carrito/cantidadProductos/{ID_Cliente}', [$carritoController, 'getQuantityProductsCart']);
$router->add('POST', '/carrito/agregar', [$carritoController, 'addProducto']);
$router->add('DELETE', '/carrito/remover', [$carritoController, 'removeProducto']);
$router->add('DELETE', '/carrito/vaciar', [$carritoController, 'clearCarrito']);
$router->add('PATCH', '/carrito/actualizar', [$carritoController, 'updateCantidad']);

// RUTAS PARA PRODUCTOS
$router->add('POST', '/productos', [$productoController, 'create']);
$router->add('PUT', '/productos/{id}', [$productoController, 'update']);
$router->add('DELETE', '/productos/{id}', [$productoController, 'delete']);
$router->add('GET', '/productos', [$productoController, 'getProductos']);
$router->add('GET', '/productos/{id}', [$productoController, 'getProductoById']);
$router->add('GET', '/productos/categoria/{id_Categoria}', [$productoController, 'getProductosByCategoria']);
$router->add('GET', '/productos/marca/{id_Marca}', [$productoController, 'getProductosByMarca']);
$router->add('PATCH', '/productos/{id}', [$productoController, 'updateStock']);
$router->add('POST', '/productos/imagen', [$productoController, 'uploadImage']);

// RUTAS PARA CATEGORÍAS
$router->add('POST', '/categorias', [$categoriaController, 'create']);
$router->add('PUT', '/categorias/{id}', [$categoriaController, 'update']);
$router->add('DELETE', '/categorias/{id}', [$categoriaController, 'delete']);
$router->add('GET', '/categorias', [$categoriaController, 'getCategorias']);
$router->add('GET', '/categorias/{id}', [$categoriaController, 'getCategoriaById']);

?>