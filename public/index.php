<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload für Controller und Models
spl_autoload_register(function ($class) {
    $paths = ['app/controllers/', 'app/models/'];
    foreach ($paths as $path) {
        $file = __DIR__ . '/../' . $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Fallback-Page
$page = $_GET['page'] ?? 'home';

// Routing
switch ($page) {
    case 'home':
        $controller = new HomeController();
        $controller->index();
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

    case 'profile':
        $controller = new UserController();
        $controller->profile();
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

    case 'cart':
        (new CartController())->showCart();
        break;
    case 'add-cart-item':
        (new CartController())->addItem();
        break;
    case 'get-cart':
        (new CartController())->getCart();
        break;
    case 'update-cart-item':
        (new CartController())->updateItem();
        break;
    case 'remove-cart-item':
        (new CartController())->removeItem();
        break;
    case 'clear-cart':
        (new CartController())->clearCart();
        break;

    case 'merge-cart':
        (new CartController())->mergeCart();
        break;

    case 'checkout':
        (new CheckoutController())->checkout();
        break;

    case 'apply-coupon':
        (new CouponController())->apply();
        break;

    case 'set-cart-total':
    (new CouponController())->setCartTotal();
    break;

    case 'place-order':
    (new CheckoutController())->placeOrder();
    break;






    default:
        echo "Seite nicht gefunden.";
}
