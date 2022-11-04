<?php

require_once 'libs/Router.php';
require_once 'Controller/ApiController.php';

// crea el router
$router = new Router();

// define la tabla de ruteo
$router->addRoute('products', 'GET', 'ApiController', 'getProducts');
$router->addRoute('products/:ID', 'GET', 'ApiController', 'getProductsByCat');
$router->addRoute('product/:ID', 'GET', 'ApiController', 'getProduct');
$router->addRoute('product', 'POST', 'ApiController', 'createProduct');
$router->addRoute('product/:ID', 'DELETE', 'ApiController', 'deleteProduct');
$router->addRoute('product/:ID', 'PUT', 'ApiController', 'updateProduct');

// rutea
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);

