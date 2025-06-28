<?php
/**
 * AboutController
 *
 * Enthält die Logik zum Laden von Bildern
 * für die Aboutseite. Verwendet das AboutModel zur Datenabfrage.
 *
 * @author Nick Zetzmann
 */

require_once '../app/models/AboutModel.php';

class AboutController {
    public function about() {
        $Felix = AboutModel::getFelix();
        $Marvin = AboutModel::getMarvin();
        $Merzan = AboutModel::getMerzan();
        $Nick = AboutModel::getNick();

        include '../app/views/pages/about.php';
    }
}