<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Datenschutzerklärung</title>

  <!-- CSS -->
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/global.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/navbar_transparent.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/footer.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/cookieBanner.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/datenschutzerklaerung.css" />

  <!-- JS -->
  <script src="<?= BASE_URL ?>/js/navbar.js" defer></script>
  <script src="<?= BASE_URL ?>/js/cookieBanner.js" defer></script>
</head>

<body>
  <?php include __DIR__ . '/../layouts/navbar.php'; ?>

  <main class="container content-wrapper">
    <h2>Datenschutzerklärung</h2>

    <p>Wir freuen uns sehr über Ihr Interesse an unserem Webshop. Der Schutz Ihrer personenbezogenen Daten ist uns ein
      wichtiges Anliegen. Nachfolgend informieren wir Sie ausführlich über den Umgang mit Ihren Daten.</p>

    <h3>1. Verantwortlicher</h3>
    <p>Verantwortlicher im Sinne der Datenschutz-Grundverordnung (DSGVO) ist:</p>
    <address>
      Felix Bartel<br />
      Esplanade 10<br />
      85057 Ingolstadt<br />
      E-Mail: info@xpn.de<br />
    </address>

    <h3>2. Erhebung und Speicherung personenbezogener Daten sowie Art und Zweck der Verwendung</h3>
    <h4>a. Beim Besuch der Website</h4>
    <p>Beim Aufrufen unserer Website werden durch den Browser automatisch Informationen an den Server unserer Website
      gesendet. Diese Informationen werden temporär in einem sogenannten Logfile gespeichert. Folgende Informationen
      werden dabei ohne Ihr Zutun erfasst und bis zur automatisierten Löschung gespeichert:</p>
    <ul>
      <li>IP-Adresse des anfragenden Rechners</li>
      <li>Datum und Uhrzeit des Zugriffs</li>
      <li>Name und URL der abgerufenen Datei</li>
      <li>Website, von der aus der Zugriff erfolgt (Referrer-URL)</li>
      <li>verwendeter Browser und ggf. das Betriebssystem Ihres Rechners</li>
      <li>Name Ihres Access-Providers</li>
    </ul>
    <p>Diese Daten dienen lediglich statistischen Auswertungen und der Verbesserung der Website. Eine Zusammenführung
      dieser Daten mit anderen Datenquellen wird nicht vorgenommen.</p>

    <h4>b. Bei Nutzung unseres Webshops</h4>
    <p>Wenn Sie in unserem Webshop eine Bestellung aufgeben, erfassen wir die von Ihnen angegebenen personenbezogenen
      Daten, wie Name, Adresse, E-Mail-Adresse und Zahlungsinformationen, um die Bestellung abzuwickeln und Ihnen die
      bestellte Ware zu liefern.</p>

    <h3>3. Weitergabe von Daten</h3>
    <p>Eine Übermittlung Ihrer persönlichen Daten an Dritte zu anderen als den im Folgenden aufgeführten Zwecken findet
      nicht statt. Wir geben Ihre persönlichen Daten nur an Dritte weiter, wenn:</p>
    <ul>
      <li>Sie Ihre ausdrückliche Einwilligung dazu erteilt haben,</li>
      <li>die Weitergabe zur Abwicklung des Vertragsverhältnisses erforderlich ist,</li>
      <li>die Weitergabe zur Erfüllung einer rechtlichen Verpflichtung erforderlich ist,</li>
      <li>die Weitergabe zur Wahrung berechtigter Interessen erforderlich ist und kein Grund zur Annahme besteht, dass
        Sie ein überwiegendes schutzwürdiges Interesse an der Nichtweitergabe Ihrer Daten haben.</li>
    </ul>

    <h3>4. Cookies</h3>
    <p>Unsere Website verwendet Cookies, um die Benutzerfreundlichkeit zu erhöhen und bestimmte Funktionen bereitzustellen.
      Cookies sind kleine Textdateien, die auf Ihrem Endgerät gespeichert werden. Sie können die Speicherung von Cookies
      in Ihren Browsereinstellungen verhindern oder einschränken.</p>

    <h3>5. Google Analytics</h3>
    <p>Diese Website benutzt Google Analytics, einen Webanalysedienst der Google LLC. Google Analytics verwendet Cookies,
      die eine Analyse der Benutzung der Website durch Sie ermöglichen. Die durch das Cookie erzeugten Informationen
      über Ihre Benutzung dieser Website werden in der Regel an einen Server von Google in den USA übertragen und dort
      gespeichert.</p>
    <p>Wir haben die IP-Anonymisierung aktiviert, so dass Ihre IP-Adresse von Google innerhalb von Mitgliedstaaten der
      Europäischen Union oder in anderen Vertragsstaaten des Abkommens über den Europäischen Wirtschaftsraum zuvor
      gekürzt wird.</p>
    <p>Sie können die Speicherung der Cookies durch eine entsprechende Einstellung Ihrer Browser-Software verhindern;
      wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall gegebenenfalls nicht sämtliche Funktionen dieser Website
      vollumfänglich nutzen können.</p>
    <p>Sie können darüber hinaus die Erfassung der durch das Cookie erzeugten und auf Ihre Nutzung der Website bezogenen
      Daten (inkl. Ihrer IP-Adresse) an Google sowie die Verarbeitung dieser Daten durch Google verhindern, indem Sie
      das unter dem folgenden Link Cookies deaktivieren: <br />
    <a href="#" onclick="showBanner(); return false;">Cookie Banner</a>
    </p>

    <h3>6. Rechte der betroffenen Person</h3>
    <p>Sie haben das Recht:</p>
    <ul>
      <li>gemäß Art. 15 DSGVO Auskunft über Ihre von uns verarbeiteten personenbezogenen Daten zu verlangen,</li>
      <li>gemäß Art. 16 DSGVO unverzüglich die Berichtigung unrichtiger oder Vervollständigung Ihrer bei uns gespeicherten
        personenbezogenen Daten zu verlangen,</li>
      <li>gemäß Art. 17 DSGVO die Löschung Ihrer bei uns gespeicherten personenbezogenen Daten zu verlangen, soweit nicht
        die Verarbeitung zur Ausübung des Rechts auf freie Meinungsäußerung und Information, zur Erfüllung einer rechtlichen
        Verpflichtung, aus Gründen des öffentlichen Interesses oder zur Geltendmachung, Ausübung oder Verteidigung von
        Rechtsansprüchen erforderlich ist,</li>
      <li>gemäß Art. 18 DSGVO die Einschränkung der Verarbeitung Ihrer personenbezogenen Daten zu verlangen,</li>
      <li>gemäß Art. 20 DSGVO Ihre personenbezogenen Daten, die Sie uns bereitgestellt haben, in einem strukturierten, gängigen
        und maschinenlesbaren Format zu erhalten oder die Übermittlung an einen anderen Verantwortlichen zu verlangen,</li>
      <li>gemäß Art. 7 Abs. 3 DSGVO Ihre einmal erteilte Einwilligung jederzeit gegenüber uns zu widerrufen,</li>
      <li>gemäß Art. 77 DSGVO sich bei einer Aufsichtsbehörde zu beschweren.</li>
    </ul>

    <h3>7. Aktualität und Änderung dieser Datenschutzerklärung</h3>
    <p>Diese Datenschutzerklärung ist aktuell gültig und hat den Stand Mai 2025. Durch die Weiterentwicklung unserer Website
      und Angebote darüber oder aufgrund geänderter gesetzlicher beziehungsweise behördlicher Vorgaben kann es notwendig
      werden, diese Datenschutzerklärung zu ändern.</p>
  </main>
  <?php include __DIR__ . '/../layouts/cookieBanner.php'; ?>
  <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>