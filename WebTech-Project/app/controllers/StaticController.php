<?php

class StaticController {
    public function impressum() {
        require_once '../app/views/pages/impressum.php';
    }

    public function datenschutzerklaerung() {
        require_once '../app/views/pages/datenschutzerklaerung.php';
    }

    public function about() {
        require_once '../app/views/pages/about.php';
    }
}
