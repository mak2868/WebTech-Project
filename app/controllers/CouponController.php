<?php
class CouponController
{
    public function apply()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $code = trim($data['code'] ?? '');

        if (!$code) {
            echo json_encode(['success' => false, 'message' => 'Kein Code übergeben.']);
            return;
        }

        require_once '../app/lib/DB.php';
        $db = DB::getConnection();

        $stmt = $db->prepare("SELECT * FROM coupons WHERE code = :code AND (valid_until IS NULL OR valid_until >= NOW())");
        $stmt->execute(['code' => $code]);
        $coupon = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($coupon) {
            $_SESSION['coupon'] = [
                'code' => $coupon['code'],
                'type' => $coupon['discount_type'], // muss z. B. "percent" oder "amount" sein
                'value' => (float)$coupon['discount_value']
            ];
            echo json_encode(['success' => true]);
        } else {
            unset($_SESSION['coupon']);
            echo json_encode(['success' => false, 'message' => 'Ungültiger Code.']);
        }
    }
}
