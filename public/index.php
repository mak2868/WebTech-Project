<?php
// Session und Config laden
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/config/config.php';

// Favicon-Daten holen (für spätere Übergabe an Views)
require_once __DIR__ . '/../app/controllers/InitialController.php';
$initialController = new InitialController();
$symbolData = $initialController->getFenstersymbols();
$_SESSION['fenstersymbolData'] = $symbolData;


// Navbar-Daten (Kategorien + Bilder) laden
require_once __DIR__ . '/../app/controllers/NavbarController.php';
$navbarController = new NavbarController();
$navbarData = $navbarController->getNavbarData();
$_SESSION['navbarData'] = $navbarData;


// Footer-Daten (Links + Social Icons) laden
require_once __DIR__ . '/../app/controllers/FooterController.php';
FooterController::prepareFooterData();


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



// Newsletter
if ($page === 'newsletterSignup') {
    $controller = new NewsletterController();
    $controller->handleSignup();
    exit;
}

// Routing
switch ($page) {
    case 'home':
        (new HomeController())->index();
        break;
    case 'product':
        (new ProductController())->detail($_GET['id'] ?? null);
        break;
    case 'login':
        (new UserController())->login();
        break;
    case 'logout':
        (new UserController())->logout();
        break;
    case 'register':
        (new UserController())->register();
        break;
    case 'profile':
        (new UserController())->profile();
        break;
    case 'impressum':
        (new StaticController())->impressum();
        break;
    case 'datenschutzerklaerung':
        (new StaticController())->datenschutzerklaerung();
        break;
    case 'about':
        (new StaticController())->about();
        break;
    case 'productList':
        $controller = new ProductController();
        $controller->showProducts();
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
        (new CheckoutController())->showCheckout();
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

    case 'admin-update-user-field':
        $controller = new AdminController();
        $controller->updateUserData();
        break;

    case 'admin-delete-user':
        $controller = new AdminController();
        $controller->deleteUser();
        break;

    case 'admin-show-parent-category':
        $controller = new AdminController();
        $controller->showAllParentCategories();
        break;

    case 'admin-add-parent-category':
        $controller = new AdminController();
        $controller->addParentCategory();
        break;

    default:
        echo "Seite nicht gefunden.";
}
?>