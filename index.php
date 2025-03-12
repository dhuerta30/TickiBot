<?php
require('vendor/autoload.php');
$dotenv = DotenvVault\DotenvVault::createImmutable(__DIR__);
$dotenv->safeLoad();
require __DIR__ . '/app/libs/' . $_ENV["url_artify"];
require __DIR__ . '/app/libs/docufy/docufy.php';

use App\core\ArtifyRouter;
use App\core\Request;

$router = new ArtifyRouter();

// Definir rutas
$router->get('/', 'LoginController@index');
$router->get('/login', 'LoginController@index');
$router->get('/salir', 'LoginController@salir');
$router->get('/recuperar', 'LoginController@reset');

$router->get('/modulos', 'HomeController@modulos');
$router->get('/bot', 'HomeController@bot');
$router->get('/chat', 'HomeController@historico_chat');
$router->get('/usuarios', 'HomeController@usuarios');
$router->get('/perfil', 'HomeController@perfil');
$router->get('/respaldos', 'HomeController@respaldos');
$router->get('/menu', 'HomeController@menu');
$router->get('/acceso_menus', 'HomeController@acceso_menus');
$router->post('/generarToken', 'HomeController@generarToken');
$router->post('/obtener_campos_relacion_union_interna', 'HomeController@obtener_campos_relacion_union_interna');
$router->post('/obtener_id_tabla', 'HomeController@obtener_id_tabla');
$router->post('/obtener_tablas', 'HomeController@obtener_tablas');
$router->post('/obtener_columnas_tabla', 'HomeController@obtener_columnas_tabla');
$router->post('/obtener_tabla_id', 'HomeController@obtener_tabla_id');
$router->post('/refrescarMenu', 'HomeController@refrescarMenu');
$router->post('/generar_datos_usuario', 'HomeController@generar_datos_usuario');
$router->post('/export_db', 'HomeController@export_db');
$router->post('/editar_iconos_menu', 'HomeController@editar_iconos_menu');
$router->post('/actualizar_orden_menu', 'HomeController@actualizar_orden_menu');
$router->post('/actualizar_orden_submenu', 'HomeController@actualizar_orden_submenu');
$router->post('/asignar_menus_usuario', 'HomeController@asignar_menus_usuario');
$router->post('/obtener_menu_usuario', 'HomeController@obtener_menu_usuario');
$router->post('/obtener_campos_union_izquierda', 'HomeController@obtener_campos_union_izquierda');
$router->post('/cargar_imagenes_configuracion', 'HomeController@cargar_imagenes_configuracion');
$router->post('/cargar_vista_submenu', 'HomeController@cargar_vista_submenu');
$router->post('/mensajes', 'HomeController@mensajes');

$router->get('/Configuracion', 'ConfiguracionController@index');

$router->get('/documentacion', 'DocumentacionController@documentacion');
$router->get('/error', 'ErrorController@index');

$router->get('/hola', 'UserController@index');
$router->get('usuario/{id}/{val}/{val}', 'UserController@show');

/* Api Controllers */
$router->post('/Restp/generarToken', 'RestpController@generarToken');

$router->get('/Restp/listar/{tabla}/{token}', 'RestpController@listar');
$router->get('/Restp/listar/{tabla}/{filtro_url}/{token}', 'RestpController@listar');

$router->post('/Restp/insertar', 'RestpController@insertar');
$router->post('/Restp/actualizar', 'RestpController@actualizar');
$router->post('/Restp/eliminar', 'RestpController@eliminar');

$additionalRoutesPath = __DIR__ . '/app/core/extra_routes.php';

if (file_exists($additionalRoutesPath)) {
    $additionalRoutes = require $additionalRoutesPath;
    $additionalRoutes($router);
}

$request = new Request();
$router->dispatch($request);
