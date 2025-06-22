<?php
require_once __DIR__ . '/../models/InitialModel.php';

class InitialController {

    public function getFenstersymbols() {
    
        return  InitialModel::getFenstersymbols();

    }
}