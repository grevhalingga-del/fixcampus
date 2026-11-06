<?php
/**
 * Front controller FixCampus - semua permintaan masuk lewat berkas ini.
 * Buka: http://localhost/fixcampus/public/
 */
require __DIR__ . '/../app/bootstrap.php';

Router::dispatch();
