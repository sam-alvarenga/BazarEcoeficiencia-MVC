<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');

//rotas Cadastrar Usuarios
$router->get('/cadUsuario', 'HomeController@sobre');
$router->post('/cadUsuario','HomeController@');

//rotas Cadastrar Doação
$router->get('/cadDoacao', 'HomeController@sobre');
$router->post('/cadDoacao', 'HomeController@sobre');

//rotas das Vendas
$router->get('/venda', 'HomeController@sobre');
$router->post('/venda','HomeController@sobre');

//Saldo
$router->get('/verSaldo', 'HomeController@sobre');




/* $router->get('/sobre/{nome}', 'HomeController@sobreP');
$router->get('/sobre', 'HomeController@sobre'); */