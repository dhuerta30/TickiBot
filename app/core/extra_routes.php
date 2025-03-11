<?php

use App\Core\ArtifyRouter;

return function(ArtifyRouter $router) {
    $router->get('/Clientes', 'ClientesController@Modulo_de_Clientes');

    $router->get('/Inventario', 'InventarioController@Inventario_de_Productos');

    $router->get('/Personas', 'PersonasController@Modulo_de_Personas');
};