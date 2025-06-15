<?php
include_once __DIR__ . '/../src/controllers/UsuarioController.php';
include_once __DIR__ . '/../src/controllers/PedidoController.php';
include_once __DIR__ . '/../src/controllers/ProductoPedidoController.php';
include_once __DIR__ . '/../src/controllers/DatosEnvioController.php';
include_once __DIR__ . '/../src/controllers/MarcaController.php';
include_once __DIR__ . '/../src/controllers/CarritoController.php';

$usuarioController = new UsuarioController();
$pedidoController = new PedidoController();
$productoPedidoController = new ProductoPedidoController();
$datosEnvioController = new DatosEnvioController();
$marcaController = new MarcaController();
$carritoController = new CarritoController();

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
$router->add('PUT','/cambiopassdesdedetalles', [$usuarioController, 'cambiopassdesdeDetalles']);

// RUTAS PARA PEDIDOS
$router->add('GET', '/pedidos', [$pedidoController, 'getPedidos']);
$router->add('GET', '/pedidos/{id}', [$pedidoController, 'getPedidoById']);
$router->add('GET', '/pedidos/cliente/{id}', [$pedidoController, 'getPedidoByCliente']);
$router->add('POST', '/pedidos', [$pedidoController, 'create']);
$router->add('PATCH', '/pedidos/{id}', [$pedidoController, 'updateStatus']);
$router->add('DELETE', '/pedidos/{id}', [$pedidoController, 'cancel']);

// RUTAS PARA PRODUCTOS PEDIDOS
$router->add('GET', '/productos/pedido/{id_pedido}', [$productoPedidoController, 'getProductoPedidoByIdPedido']);

// RUTAS PARA DATOS DE ENVIO
$router->add('POST', '/pedido/datosenvio', [$datosEnvioController, 'create']);

// RUTAS PARA MARCAS
$router->add('GET', '/marcas', [$marcaController, 'getMarcas']);
$router->add('POST', '/marcas', [$marcaController, 'create']);

// RUTAS PARA CARRITO
$router->add('GET', '/carrito/{id_usuario}', [$carritoController, 'getCarrito']);
$router->add('POST', '/carrito/agregar', [$carritoController, 'addProducto']);
$router->add('DELETE', '/carrito/remover', [$carritoController, 'removeProducto']);
$router->add('DELETE', '/carrito/vaciar', [$carritoController, 'clearCarrito']);
$router->add('PATCH', '/carrito/actualizar', [$carritoController, 'updateCantidad']);

?>