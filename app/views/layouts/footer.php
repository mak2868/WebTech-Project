<?php require_once __DIR__ . '/../../config/config.php'; ?>
<section class="footer-subscribe">
  <div class="container">
    <div class="footer-form">
      <form class="footer-form-container" method="get">
        <div class="footer-form-title">Jetzt Newsletter abonnieren</div>
        <div class="footer-form-block">
          <input class="footer-form-input" type="email" placeholder="E-Mail eingeben" required />
          <input type="submit" class="footer-form-button" value="Jetzt bestellen" />
        </div>
      </form>
      <div class="form-success" style="display: none;">Danke! Deine Anmeldung geht bei uns ein!</div>
      <div class="form-error" style="display: none;">Oh! Hier ist etwas schiefgelaufen!</div>
    </div>

    <div class="footer-wrapper">
      <div class="footer-links">
        <a href="<?= BASE_URL ?>/?page=about">About</a>
        <a href="<?= BASE_URL ?>/?page=ProteinpulverList">Proteinpulver</a>
        <a href="<?= BASE_URL ?>/?page=ProteinriegelList">Proteinriegel</a>
        <a href="#">FAQ</a>
        <a href="#">Kontakt</a>
      </div>
      <div class="footer-social">
        <a href="#"><img src="<?= BASE_URL ?>/images/facebook.png" alt="Facebook" /></a>
        <a href="#"><img src="<?= BASE_URL ?>/images/youtube.png" alt="YouTube" /></a>
        <a href="#"><img src="<?= BASE_URL ?>/images/instagram.png" alt="Instagram" /></a>
        <a href="#"><img src="<?= BASE_URL ?>/images/tiktok.png" alt="TikTok" /></a>
      </div>
    </div>

    <div class="footer-divider"></div>
    <div class="footer-bottom">
      <div class="footer-copyright">© 2025 EXTREM PERFORMANCE NUTRITION. All rights reserved</div>
      <div class="footer-legal">
        <a href="<?= BASE_URL ?>/?page=datenschutzerklaerung">Datenschutz</a>
        <a href="<?= BASE_URL ?>/?page=impressum">Impressum</a>
      </div>
    </div>
  </div>
</section>

<!-- JS für den Footer (falls du einen brauchst) -->
<script src="<?= BASE_URL ?>/js/footer.js"></script>
