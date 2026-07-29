<?php
declare(strict_types=1);

/**
 * Front controller. Toate cererile ajung aici prin .htaccess.
 *
 * Nu exista alt fisier .php accesibil public in afara de acesta — restul
 * codului sta in src/, deasupra radacinii web.
 */

$radacina = dirname(__DIR__);

require $radacina . '/src/helpers.php';
require $radacina . '/src/Database.php';
require $radacina . '/src/Router.php';
require $radacina . '/src/Markdown.php';
require $radacina . '/src/Export.php';
require $radacina . '/src/Imagine.php';
require $radacina . '/src/Auth.php';
require $radacina . '/src/models/Articol.php';
require $radacina . '/src/models/Faq.php';
require $radacina . '/src/models/Resursa.php';
require $radacina . '/src/models/Mesaj.php';
require $radacina . '/src/models/Psiholog.php';
require $radacina . '/src/controllers/public.php';
require $radacina . '/src/controllers/admin.php';

// --- Raportarea erorilor ---------------------------------------------------
// In productie nimic nu ajunge pe ecran: un stack trace expune caile de pe
// server si structura bazei de date.
if (config('site', 'mediu') === 'dev') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED);
}

// --- Sesiune ---------------------------------------------------------------
// Pornita inainte de rutare, pentru ca si paginile publice au nevoie de ea
// (token CSRF la formularul de contact, starea consimtamantului la cookies).
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => !empty($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_name('sesiune');
session_start();

// --- Antete de securitate --------------------------------------------------
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: SAMEORIGIN');
// Politica de permisiuni: site-ul nu are nevoie de niciuna dintre acestea.
header('Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=(), interest-cohort=()');

$router = new Router();

// --- Pagini publice --------------------------------------------------------
$router->get('/',                            'pagina_acasa');
$router->get('/cum-functioneaza',            'pagina_cum_functioneaza');
$router->get('/servicii',                    'pagina_servicii');
$router->get('/echipa',                      'pagina_echipa');
// Vechea rută rămâne, redirecționează la Echipa (linkuri vechi, SEO).
$router->get('/despre-mine',                 'pagina_despre_redirect');
$router->get('/resurse',                     'pagina_resurse');
$router->get('/contact',                     'pagina_contact');
$router->post('/contact',                    'trimite_contact');

// Articole. Rutele specifice sunt inaintea celei cu parametru liber.
$router->get('/articole',                    'pagina_articole');
$router->get('/articole/pagina/{numar}',     'pagina_articole');
$router->get('/articole/categorie/{slug}',   'pagina_categorie');
$router->get('/articol/{slug}',              'pagina_articol');

// Pagini legale
$router->get('/politica-de-confidentialitate', 'pagina_confidentialitate');
$router->get('/politica-de-cookies',           'pagina_cookies');
$router->get('/termeni-si-conditii',           'pagina_termeni');

// Fisiere generate
$router->get('/sitemap.xml',                 'genereaza_sitemap');
$router->get('/robots.txt',                  'genereaza_robots');

// --- Admin -----------------------------------------------------------------
$router->get('/admin',                       'admin_tablou');
$router->get('/admin/autentificare',         'admin_formular_login');
$router->post('/admin/autentificare',        'admin_proceseaza_login');
$router->post('/admin/iesire',               'admin_iesire');

$router->get('/admin/articole',              'admin_lista_articole');
$router->get('/admin/articole/nou',          'admin_formular_articol');
$router->get('/admin/articole/{id}',         'admin_formular_articol');
$router->post('/admin/articole/salveaza',    'admin_salveaza_articol');
$router->post('/admin/articole/sterge',      'admin_sterge_articol');
$router->get('/admin/previzualizare/{id}',   'admin_previzualizare');

$router->post('/admin/incarca-imagine',      'admin_incarca_imagine');
$router->get('/admin/setari',                'admin_setari');
$router->post('/admin/setari',               'admin_salveaza_setari');
$router->get('/admin/faq',                   'admin_faq');
$router->post('/admin/faq',                  'admin_salveaza_faq');
$router->get('/admin/resurse',               'admin_resurse');
$router->post('/admin/resurse',              'admin_salveaza_resurse');
$router->get('/admin/psihologi',             'admin_psihologi');
$router->post('/admin/psihologi',            'admin_salveaza_psihologi');
$router->get('/admin/mesaje',                'admin_mesaje');
$router->post('/admin/mesaje/sterge',        'admin_sterge_mesaj');
$router->post('/admin/exporta',              'admin_exporta_markdown');

$router->notFound('pagina_negasita');

$router->dispatch(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    $_SERVER['REQUEST_URI'] ?? '/'
);
