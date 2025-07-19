<?php
require_once __DIR__ . '/vendor/autoload.php';

use CKSource\CKFinder\CKFinder;

//  Load konfigurasi dan simpan ke variabel
$config = require_once 'C:/xampp/htdocs/crud-php/assets/ckfinder/config.php';

//  Buat instance CKFinder dan jalankan
$ckfinder = new CKFinder($config);
$ckfinder->run();
