<?php
/**
 * Übergibt Daten an view (footer.php)
 * Manipuliert Model (NewsletterModel)
 * @author: Felix Bartel
 */
?>



<?php
require_once __DIR__ . '/../models/NewsletterModel.php';

class NewsletterController {
  public function handleSignup() {
    header('Content-Type: application/json');

    $input = json_decode(file_get_contents("php://input"), true);
    $email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);

    if (!$email) {
      echo json_encode([
        'success' => false,
        'message' => 'Ungültige E-Mail-Adresse.'
      ]);
      return;
    }

    $model = new NewsletterModel();
    $saveResult = $model->saveEmail($email);

    if ($saveResult === true) {
      echo json_encode(['success' => true, 'message' => '🎉 Erfolgreich angemeldet, bleib gespannt!']);
    } elseif ($saveResult === 'duplicate') {
      echo json_encode(['success' => false, 'message' => 'Du erhältst für diese E-Mail Adresse bereits unseren Newsletter.']);
    } else {
      echo json_encode(['success' => false, 'message' => 'Ein Fehler ist aufgetreten.']);
    }
  }
}
