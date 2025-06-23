<?php

require_once __DIR__ . '/../models/FooterModel.php';

class FooterController {
    public static function prepareFooterData() {
        // Globalen Key setzen, damit footer.php darauf zugreifen kann
        $GLOBALS['footerSocialIcons'] = FooterModel::getSocialIcons();
    }
}
