<?php
/**
 * footer.php (view)
 * @author: Felix Bartel
 */
?>




<?php require_once __DIR__ . '/../../config/config.php'; ?>

<?php
  $icons = $GLOBALS['footerSocialIcons'] ?? [];
  $footerLinks = $GLOBALS['footerLinks'] ?? [];
?>

<section class="footer-subscribe">
  <div class="container">
    <div class="footer-form">
      <form id="newsletterForm" class="footer-form-container">
        <div class="footer-form-title">Jetzt Newsletter abonnieren</div>
        <div class="footer-form-block">
          <input name="email" class="footer-form-input" type="email" placeholder="E-Mail eingeben" required />
          <input type="submit" class="footer-form-button" value="Jetzt bestellen" />
        </div>
      </form>

      <div class="form-success" style="display: none;"></div>
      <div class="form-error" style="display: none;"></div>

      <div class="footer-wrapper">
        <div class="footer-links">
          <a href="<?= BASE_URL ?>/?page=faq">FAQ</a>
          <a href="<?= BASE_URL ?>/?page=kontakt">Kontakt</a>
          <?php foreach ($footerLinks as $link): ?>
            <a href="<?= BASE_URL ?>/index.php?page=productList&parentId=<?= urlencode($link['id']) ?>">
              <?= htmlspecialchars($link['name']) ?>
              </a>
            <?php endforeach; ?>
 
        </div>
        <div class="footer-social">
          <?php foreach ($icons as $icon): ?>                                                   
            <a href="#"><img src="<?= BASE_URL . $icon ?>" alt="Social Icon" /></a>               
          <?php endforeach; ?> 
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
  </div>
</section>

<script src="<?= BASE_URL ?>/js/footer.js"></script>
