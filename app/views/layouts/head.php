<?php
$symbolData = $_SESSION['fenstersymbolData'] ?? [];
?>

<!-- CSS: No-JavaScript Hinweis -->
<link rel="stylesheet" href="<?= BASE_URL ?>/css/no-js.css">

<!-- Favicon + Symboldaten -->
<script src="<?= BASE_URL ?>/js/initial.js" defer></script>
<link id="fenstersymbol" rel="icon" type="image/png" href="">

<script>
  const fenstersymbolData = <?= json_encode($symbolData, JSON_UNESCAPED_UNICODE) ?>;
  window.onload = () => initializeFenstersymbol();
</script>
