<?php
include_once __DIR__ . '/../src/controllers/UsuarioController.php';
include_once __DIR__ . '/../src/controllers/PedidoController.php';
include_once __DIR__ . '/../src/controllers/ProductoPedidoController.php';
include_once __DIR__ . '/../src/controllers/DatosEnvioController.php';
include_once __DIR__ . '/../src/controllers/MarcaController.php';

$usuarioController = new UsuarioController();
$pedidoController = new PedidoController();
$productoPedidoController = new ProductoPedidoController();
$datosEnvioController = new DatosEnvioController();
$marcaController = new MarcaController();


// RUTAS PARA USUARIOS
$router->add('GET', '/usuarios', [$usuarioController, 'getUsuarios']);
$router->add('GET', '/usuarios/{id}', [$usuarioController, 'getUsuarioById']);
$router->add('POST', '/usuarios', [$usuarioController, 'create']);
$router->add('POST','/inisiarsesion', [$usuarioController,'inisiarsesion']);
$router->add('PUT','/recuperarpassword', [$usuarioController,'RecuperarPassword']);
$router->add('PUT','/cambiopassword', [$usuarioController,'CambioPassword']);

// RUTAS PARA PEDIDOS
$router->add('GET', '/pedidos', [$pedidoController, 'getPedidos']);
$router->add('GET', '/pedidos/{id}', [$pedidoController, 'getPedidoById']);
$router->add('GET', '/pedidos/cliente/{id}', [$pedidoController, 'getPedidoByCliente']);
$router->add('POST', '/pedidos', [$pedidoController, 'create']);
$router->add('PATCH', '/pedidos/{id}', [$pedidoController, 'updateStatus']);
$router->add('DELETE', '/pedidos/{id}', [$pedidoController, 'cancel']);

// RUTAS PARA PRODUCTOS PEDIDOS
$router->add('GET', '/productos/pedido/{id_pedido}', [$productoPedidoController, 'getProductoPedidoByIdPedido']);
$router->add('POST', '/productos/pedido', [$productoPedidoController, 'create']);

// RUTAS PARA DATOS DE ENVIO
$router->add('POST', '/pedido/datosenvio', [$datosEnvioController, 'create']);

// RUTAS PARA MARCAS
$router->add('POST', '/marcas', [$marcaController, 'create']);

?>