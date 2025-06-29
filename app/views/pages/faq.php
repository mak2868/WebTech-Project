<!-- erstellt von: Nick Zetzmann -->

<?php require_once __DIR__ . '/../../config/config.php'; ?>

<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>XPN | FAQ</title>

  <!-- CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/faq.css">

  <!-- JS -->
  <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>
  <script src="<?= BASE_URL ?>/js/faq.js" defer></script>

  <!-- Head-Datei -->
  <?php include __DIR__ . '/../layouts/head.php'; ?>
</head>

<body>
  <?php include __DIR__ . '/../layouts/navbar.php'; ?>

    <main class="faq-container">
        <h1>Häufig gestellte Fragen (FAQ)</h1>

<!-- Jede Frage besteht aus einem Button und einem versteckten Antwortbereich -->
<!-- 'aria' sorgt für Barrierefreiheit: screenreader koennen erkennen, ob die Antwort sichtbar ist -->
<!-- auch ohne 'aria' moeglich, via hidden: z.B. hier faq-answer hidden; CSS: display:none -->
<!-- und dann in der JAVAScript per Klick-Eventlistener naechstes Element, hier Antwort, auswaehlen und CSS hidden per toggle umschalten, folglich Antwort ist sichtbar  -->
       <section class="faq-item">
            <button class="faq-question" aria-expanded="false" aria-controls="faq1">Wer steht hinter XPN?</button>
            <div id="faq1" class="faq-answer" hidden>
                <p>Erfahren Sie mehr über uns auf unserer <a href="<?= BASE_URL ?>/?page=about">About</a>-Seite.</p>
            </div>
        </section>

        <section class="faq-item">
            <button class="faq-question" aria-expanded="false" aria-controls="faq2">Wie wählen wir unsere Produkte aus?</button>
            <div id="faq2" class="faq-answer" hidden>
                <p>Wir legen großen Wert auf Qualität und Transparenz. Unsere Produkte werden sorgfältig ausgewählt, basierend auf Inhaltsstoffen, Herkunft und Kundenfeedback.</p>
            </div>
        </section>

        <section class="faq-item">
            <button class="faq-question" aria-expanded="false" aria-controls="faq3">Wie erfolgt der Versand?</button>
            <div id="faq3" class="faq-answer" hidden>
                <p>Wir versenden ausschließlich innerhalb Deutschlands. Die Versandkosten entnehmen Sie bitte dem Checkout-Prozess. Die Lieferzeit beträgt in der Regel 2-5 Werktage.</p>
            </div>
        </section>

        <section class="faq-item">
            <button class="faq-question" aria-expanded="false" aria-controls="faq4">Wo finde ich das Impressum?</button>
            <div id="faq4" class="faq-answer" hidden>
                <p>Unser Impressum finden Sie <a href="<?= BASE_URL ?>/?page=impressum">hier</a>.</p>
            </div>
        </section>

        <section class="faq-item">
            <button class="faq-question" aria-expanded="false" aria-controls="faq5">Wie gehen wir mit Datenschutz um?</button>
            <div id="faq5" class="faq-answer" hidden>
                <p>Unsere Datenschutzerklärung finden Sie <a href="<?= BASE_URL ?>/?page=datenschutzerklaerung">hier</a>.</p>
            </div>
        </section>

        <section class="faq-item">
            <button class="faq-question" aria-expanded="false" aria-controls="faq6">Kann ich meine Bestellung ändern oder stornieren?</button>
            <div id="faq6" class="faq-answer" hidden>
                <p>Bestellungen können nur innerhalb von 2 Stunden nach Abgabe geändert oder storniert werden. Bitte kontaktieren Sie unseren Kundenservice so schnell wie möglich <a href="<?= BASE_URL ?>/?page=kontakt">hier</a>.</p>
            </div>
        </section>

        <section class="faq-item">
            <button class="faq-question" aria-expanded="false" aria-controls="faq7">Welche Zahlungsmethoden werden akzeptiert?</button>
            <div id="faq7" class="faq-answer" hidden>
                <p>Wir akzeptieren PayPal, Kreditkarte, Sofortüberweisung und Kauf auf Rechnung.</p>
            </div>
        </section>

        <section class="faq-item">
            <button class="faq-question" aria-expanded="false" aria-controls="faq8">Gibt es eine Produktgarantie?</button>
            <div id="faq8" class="faq-answer" hidden>
                <p>Alle Produkte sind neu und originalverpackt. Bei Qualitätsproblemen kontaktieren Sie uns bitte umgehend über unsere entsprechende Kontaktseite.</p>
            </div>
        </section>

    </main>

  <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
  <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>