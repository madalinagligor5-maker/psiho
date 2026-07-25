<?php
/**
 * Router doar pentru serverul incorporat PHP (php -S), la dezvoltare locala.
 * Pe server rolul asta il face .htaccess. NU se foloseste in productie.
 *
 *   php -S localhost:8080 -t public_html router-dev.php
 */

$cale = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$fisier = __DIR__ . '/public_html' . $cale;

// Serveste fisierele reale (css, js, fonturi, imagini) direct.
if ($cale !== '/' && is_file($fisier)) {
    return false;
}

// Tot restul merge la front controller.
require __DIR__ . '/public_html/index.php';
