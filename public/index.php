<?php

require __DIR__ . '/../src/Config/config.php';
require __DIR__ . '/../src/Core/autoload.php';

$router = new Router();

$router->get('/', function () {
    (new CatalogController())->index();
});
$router->get('/producto/{slug}', function ($params) {
    (new CatalogController())->show($params);
});
$router->get('/carrito', function () {
    (new CartController())->show();
});
$router->post('/carrito/agregar', function () {
    (new CartController())->add();
});
$router->post('/carrito/actualizar', function () {
    (new CartController())->update();
});
$router->post('/carrito/eliminar', function () {
    (new CartController())->remove();
});
$router->get('/checkout', function () {
    (new CheckoutController())->show();
});
$router->post('/checkout', function () {
    (new CheckoutController())->process();
});
$router->get('/pedido-confirmado/{id}', function ($params) {
    (new CheckoutController())->confirmation($params);
});
$router->get('/login', function () {
    (new AuthController())->showLogin();
});
$router->post('/login', function () {
    (new AuthController())->login();
});
$router->get('/registro', function () {
    (new AuthController())->showRegister();
});
$router->post('/registro', function () {
    (new AuthController())->register();
});
$router->get('/logout', function () {
    (new AuthController())->logout();
});
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
