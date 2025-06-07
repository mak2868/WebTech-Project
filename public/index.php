<?php
session_start();

// Autoload für Controller und Models
spl_autoload_register(function ($class) {
    $paths = ['../app/controllers/', '../app/models/'];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Fallback-Page, wenn keine angegeben
$page = $_GET['page'] ?? 'home';


// Routing
switch ($page) {
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;

    case 'cart':
        $controller = new CartController();
        $controller->show();
        break;

    case 'product':
        $controller = new ProductController();
        $controller->detail($_GET['id'] ?? null);
        break;

    case 'login':
        $controller = new UserController();
        $controller->login();
        break;

    case 'logout':
        $controller = new UserController();
        $controller->logout();
        break;

    case 'register':
        $controller = new UserController();
        $controller->register();
        break;

    case 'impressum':
        $controller = new StaticController();
        $controller->impressum();
        break;

    case 'datenschutzerklaerung':
        $controller = new StaticController();
        $controller->datenschutzerklaerung();
        break;

    case 'about':
        $controller = new StaticController();
        $controller->about();
        break;

    default:
        echo "Seite nicht gefunden.";
}
