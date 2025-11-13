<?php
require_once '../core/init.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'home');
    $r->addRoute('GET', '/login', 'login');
    $r->addRoute('POST', '/login', 'login_post');
    $r->addRoute('GET', '/logout', 'logout');
    $r->addRoute('GET', '/register', 'register');
    $r->addRoute('POST', '/register', 'register_post');
    $r->addRoute('GET', '/profile/{user}', 'profile');
    $r->addRoute('GET', '/update', 'update');
    $r->addRoute('POST', '/update', 'update_post');
    $r->addRoute('GET', '/changepassword', 'changepassword');
    $r->addRoute('POST', '/changepassword', 'changepassword_post');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        Redirect::to(404);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        switch ($handler) {
            case 'home':
                require 'home.php';
                break;
            case 'login':
                require 'login.php';
                break;
            case 'login_post':
                require 'login.php';
                break;
            case 'logout':
                require 'logout.php';
                break;
            case 'register':
                require 'register.php';
                break;
            case 'register_post':
                require 'register.php';
                break;
            case 'profile':
                $_GET['user'] = $vars['user'];
                require 'profile.php';
                break;
            case 'update':
                require 'update.php';
                break;
            case 'update_post':
                require 'update.php';
                break;
            case 'changepassword':
                require 'changepassword.php';
                break;
            case 'changepassword_post':
                require 'changepassword.php';
                break;
        }
        break;
}
