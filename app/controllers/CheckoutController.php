<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../app/models/UserModel.php';
class CheckoutController {
    public function show() {
        $address = null;
        if (!empty($_SESSION['user_id'])) {
            $address = UserModel::getUserAddressByUserId($_SESSION['user_id']);
        }
        include '../app/views/pages/checkout.php';
    }
}