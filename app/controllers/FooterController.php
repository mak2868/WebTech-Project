<?php

require_once __DIR__ . '/../models/FooterModel.php';


class FooterController {
    public static function prepareFooterData() {
        $GLOBALS['footerSocialIcons'] = FooterModel::getSocialIcons();
        $GLOBALS['footerLinks'] = FooterModel::getParentCategories();
    }
}

