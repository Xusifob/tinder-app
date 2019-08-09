<?php

use \Xusifob\Router;


if(!file_exists(__DIR__ . '/vendor/autoload.php')){
    die('Did you install the dependencies running composer install ?');
}

$loader = require_once "vendor/autoload.php";

if($loader instanceof \Composer\Autoload\ClassLoader){
    $loader->add('Xusifob', __DIR__ . '/vendor/xusifob/router/src/');
}


if($loader instanceof \Composer\Autoload\ClassLoader){
    $loader->add('Xusifob', __DIR__ . '/src');
}

if(!isset($_GET['url'])) {
    $_GET['url'] = '';
}

$token = null;

if(isset($_COOKIE['token'])) {
    $token = $_COOKIE['token'];
}


$_config = json_decode(file_get_contents('config/config.json'),true);

$tinder_api = new \Xusifob\Services\TinderApiService(array('X-Auth-Token' => $token));

$security = new \Xusifob\Services\TinderSecurity($token);

// An array of data to send to the controllers



try {
    $router = new Router($_GET['url'], __DIR__ . "/config/routes.json", $security);

    $config = array(
        'tinder_api' => $tinder_api,
        'security' => $security,
        'router' => $router,
        'google_api_key' => $_config['google_api_key']
    );

    $router->run($config);
}catch (\Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException $e) {
    $response = new \Symfony\Component\HttpFoundation\Response("404 Page not found",\Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
}
catch (\Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException $e) {
    $redirect =  new \Symfony\Component\HttpFoundation\RedirectResponse($router->generateUrl('home'));
    $redirect->send();
    die();
}
catch (\GuzzleHttp\Exception\ClientException $e) {
    $response = new \Symfony\Component\HttpFoundation\Response($e->getMessage(),$e->getCode());
    $response->send();
}