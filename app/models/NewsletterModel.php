<?php
require_once __DIR__ . '/../lib/DB.php';

class NewsletterModel {
  public function saveEmail($email) {
    $pdo = DB::getConnection();

    try {
      $stmt = $pdo->prepare("INSERT INTO newsletter_signups (email, signed_up_at) VALUES (:email, NOW())");
      $stmt->execute(['email' => $email]);
      return true;
    } catch (PDOException $e) {
      if ($e->getCode() == 23000) {
        // Duplicate entry
        return 'duplicate';
      }
      return false;
    }
  }
}
