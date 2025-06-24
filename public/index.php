<?php require_once __DIR__ . '/../app/config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// include __DIR__ . '/../app/controllers/InitialController.php';
// $initialController = new InitialController();
// $symbolData = $initialController->getFenstersymbols();

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

    case 'item':
        $controller = new ProductController();
        $params = $controller->validateParams();
        if ($params[0]) {
            $controller->renderItemSite($params[1], $params[2], $params[3], $params[4]);
        }
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
        (new CheckoutController())->showcheckout();
        break;

    case 'apply-coupon':
        (new CheckoutController())->applyCoupon();
        break;

    case 'set-cart-total':
        (new CheckoutController())->setCartTotal();
        break;

    case 'place-order':
        (new CheckoutController())->placeOrder();
        break;

    case 'thankyou':
        require_once '../app/views/pages/thankyou.php';
        break;

    case 'admin':
        $controller = new AdminController();
        $controller->showAdmin();
        break;

    case 'admin-users':
        $controller = new AdminController();
        $controller->userManagement();
        break;

    case 'update-user-field':
        $controller = new AdminController();
        $controller->updateUserData();
        break;

    case 'delete-user':
        $controller = new AdminController();
        $controller->deleteUser();
        break;

    default:
        echo "Seite nicht gefunden.";
}
?>