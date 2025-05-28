<?php
include_once __DIR__ . '/../src/controllers/UsuarioController.php';
include_once __DIR__ . '/../src/controllers/PedidoController.php';

$usuarioController = new UsuarioController();
$pedidoController = new PedidoController();

// RUTAS PARA USUARIOS
$router->add('GET', '/usuarios', [$usuarioController, 'getUsuarios']);
$router->add('GET', '/usuarios/{id}', [$usuarioController, 'getUsuarioById']);
$router->add('POST', '/usuarios', [$usuarioController, 'create']);

// RUTAS PARA PEDIDOS
$router->add('GET', '/pedidos', [$pedidoController, 'getPedidos']);
$router->add('GET', '/pedidos/{id}', [$pedidoController, 'getPedidoById']);
$router->add('GET', '/pedidos/cliente/{id}', [$pedidoController, 'getPedidoByCliente']);
$router->add('POST', '/pedidos', [$pedidoController, 'create']);
$router->add('PATCH', '/pedidos/{id}', [$pedidoController, 'updateStatus']);
$router->add('DELETE', '/pedidos/{id}', [$pedidoController, 'cancel']);


?>