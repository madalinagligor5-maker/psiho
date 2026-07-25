<?php
declare(strict_types=1);

/**
 * Controlerele panoului de administrare.
 *
 * Panoul e modul „Operate” din PRODUCT.md: aici miscarea e feedback si trebuie
 * sa fie buna. Clienta il foloseste singura, saptamanal. Fiecare actiune care
 * schimba ceva confirma vizibil ca s-a intamplat.
 *
 * Toate rutele, in afara de formularul de autentificare, cer Auth::cere().
 */

/** Randeaza o pagina de admin in layoutul de admin. */
function admin_render(string $sablon, array $date = []): void
{
    $date['continut'] = view('admin/' . $sablon, $date);
    $date['nr_mesaje'] = Mesaj::nrNecitite();
    $date['utilizator'] = Auth::utilizator();
    echo view('admin/layout', $date);
}

/** Mesaj scurt de confirmare, tinut in sesiune peste un redirect. */
function admin_flash(string $mesaj, string $tip = 'succes'): void
{
    $_SESSION['flash'] = ['mesaj' => $mesaj, 'tip' => $tip];
}

// ===========================================================================
// Autentificare
// ===========================================================================

function admin_formular_login(): void
{
    if (Auth::esteAutentificat()) {
        redirect('/admin');
    }
    admin_render_gol('admin/autentificare', [
        'titlu' => 'Autentificare',
        'eroare' => $_SESSION['login_eroare'] ?? null,
    ]);
    unset($_SESSION['login_eroare']);
}

function admin_proceseaza_login(): void
{
    Auth::cereCsrf();

    $email  = trim((string) ($_POST['email'] ?? ''));
    $parola = (string) ($_POST['parola'] ?? '');

    $eroare = Auth::autentifica($email, $parola);
    if ($eroare !== null) {
        $_SESSION['login_eroare'] = $eroare;
        redirect('/admin/autentificare');
    }
    redirect('/admin');
}

function admin_iesire(): void
{
    Auth::cereCsrf();
    Auth::iesire();
}

/** Layout minimal, fara meniul de admin — doar pentru pagina de login. */
function admin_render_gol(string $sablon, array $date = []): void
{
    $date['continut'] = view($sablon, $date);
    echo view('admin/layout-gol', $date);
}

// ===========================================================================
// Tablou de bord
// ===========================================================================

function admin_tablou(): void
{
    Auth::cere();

    admin_render('tablou', [
        'titlu' => 'Tablou de bord',
        'nr_articole' => (int) Database::value('SELECT COUNT(*) FROM articole'),
        'nr_ciorne'   => (int) Database::value("SELECT COUNT(*) FROM articole WHERE stare = 'ciorna'"),
        'nr_mesaje_noi' => Mesaj::nrNecitite(),
        'ultimele' => Articol::adminToate(),
    ]);
}

// ===========================================================================
// Articole
// ===========================================================================

function admin_lista_articole(): void
{
    Auth::cere();
    admin_render('articole-lista', [
        'titlu' => 'Articole',
        'articole' => Articol::adminToate(),
    ]);
}

function admin_formular_articol(string $id = ''): void
{
    Auth::cere();

    $articol = null;
    if ($id !== '' && (int) $id > 0) {
        $articol = Articol::adminDupaId((int) $id);
        if ($articol === null) {
            admin_flash('Articolul nu există.', 'eroare');
            redirect('/admin/articole');
        }
    }

    admin_render('articol-editor', [
        'titlu' => $articol ? 'Editează articol' : 'Articol nou',
        'articol' => $articol,
        'categorii' => Articol::categorii(),
    ]);
}

function admin_salveaza_articol(): void
{
    Auth::cere();
    Auth::cereCsrf();

    $id       = (int) ($_POST['id'] ?? 0);
    $titlu    = trim((string) ($_POST['titlu'] ?? ''));
    $continut = (string) ($_POST['continut'] ?? '');
    $actiune  = (string) ($_POST['actiune'] ?? 'ciorna'); // 'ciorna' sau 'publica'

    if ($titlu === '') {
        admin_flash('Articolul are nevoie de un titlu.', 'eroare');
        redirect($id > 0 ? "/admin/articole/{$id}" : '/admin/articole/nou');
    }

    // Slugul: din camp daca a fost editat, altfel din titlu. Mereu unic.
    $slugCerut = trim((string) ($_POST['slug'] ?? '')) ?: $titlu;
    $slug = Articol::slugUnic($slugCerut, $id > 0 ? $id : null);

    $stare = $actiune === 'publica' ? 'publicat' : 'ciorna';

    $categorieId = (int) ($_POST['categorie_id'] ?? 0) ?: null;
    $rezumat = trim((string) ($_POST['rezumat'] ?? ''));
    // Daca rezumatul e gol, il generam din primele randuri ale continutului.
    if ($rezumat === '' && $continut !== '') {
        $rezumat = Markdown::toText($continut, 240);
    }
    $meta = trim((string) ($_POST['meta_descriere'] ?? '')) ?: Markdown::toText($continut, 155);
    $imagineAlt = trim((string) ($_POST['imagine_alt'] ?? ''));
    $imagine = trim((string) ($_POST['imagine'] ?? '')) ?: null;

    if ($id > 0) {
        // La publicare, seteaza data doar daca nu era deja publicat.
        $eraPublicat = (string) Database::value('SELECT stare FROM articole WHERE id = ?', [$id]) === 'publicat';
        $setPublicat = ($stare === 'publicat' && !$eraPublicat) ? ', publicat_la = NOW()' : '';

        Database::run(
            "UPDATE articole SET titlu=?, slug=?, categorie_id=?, rezumat=?, continut=?,
                    imagine=?, imagine_alt=?, meta_descriere=?, stare=? {$setPublicat}
             WHERE id=?",
            [$titlu, $slug, $categorieId, $rezumat, $continut, $imagine, $imagineAlt, $meta, $stare, $id]
        );
    } else {
        $publicatLa = $stare === 'publicat' ? date('Y-m-d H:i:s') : null;
        $id = Database::insert(
            'INSERT INTO articole (titlu, slug, categorie_id, rezumat, continut, imagine, imagine_alt, meta_descriere, stare, publicat_la)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [$titlu, $slug, $categorieId, $rezumat, $continut, $imagine, $imagineAlt, $meta, $stare, $publicatLa]
        );
    }

    // La publicare, exporta imediat fisierul .md (ieșirea de siguranță).
    if ($stare === 'publicat') {
        $articol = Articol::adminDupaId($id);
        if ($articol) {
            Export::articol($articol);
        }
    }

    admin_flash($stare === 'publicat' ? 'Articol publicat.' : 'Ciornă salvată.');
    redirect("/admin/articole/{$id}");
}

function admin_sterge_articol(): void
{
    Auth::cere();
    Auth::cereCsrf();

    $id = (int) ($_POST['id'] ?? 0);
    $articol = Articol::adminDupaId($id);
    if ($articol) {
        Database::run('DELETE FROM articole WHERE id = ?', [$id]);
        Export::stergeArticol($articol['slug']);
        admin_flash('Articol șters.');
    }
    redirect('/admin/articole');
}

function admin_previzualizare(string $id): void
{
    Auth::cere();

    $articol = Articol::adminDupaId((int) $id);
    if ($articol === null) {
        pagina_negasita();
        return;
    }

    // Previzualizarea foloseste sablonul public real, ca ce vede sa fie ce va fi.
    render('public/articol', [
        'titlu'     => '[Previzualizare] ' . $articol['titlu'],
        'descriere' => $articol['meta_descriere'],
        'ruta'      => '/articole',
        'articol'   => $articol,
        'inrudite'  => [],
        'previzualizare' => true,
    ]);
}

// ===========================================================================
// Incarcare imagini (AJAX din editor)
// ===========================================================================

function admin_incarca_imagine(): void
{
    Auth::cere();
    Auth::cereCsrf();

    header('Content-Type: application/json');

    try {
        $nume = Imagine::proceseaza($_FILES['imagine'] ?? []);
        echo json_encode(['ok' => true, 'url' => url('uploads/' . $nume), 'nume' => $nume]);
    } catch (Throwable $e) {
        http_response_code(422);
        echo json_encode(['ok' => false, 'eroare' => $e->getMessage()]);
    }
}

// ===========================================================================
// Setari, FAQ, resurse
// ===========================================================================

function admin_setari(): void
{
    Auth::cere();
    admin_render('setari', [
        'titlu' => 'Setări',
        'setari' => Database::all('SELECT * FROM setari ORDER BY grup, ordine'),
    ]);
}

function admin_salveaza_setari(): void
{
    Auth::cere();
    Auth::cereCsrf();

    $valori = $_POST['setari'] ?? [];
    foreach ($valori as $cheie => $valoare) {
        Database::run('UPDATE setari SET valoare = ? WHERE cheie = ?', [(string) $valoare, (string) $cheie]);
    }
    admin_flash('Setări salvate.');
    redirect('/admin/setari');
}

function admin_faq(): void
{
    Auth::cere();
    admin_render('faq', [
        'titlu' => 'Întrebări frecvente',
        'intrebari' => Faq::adminToate(),
    ]);
}

function admin_salveaza_faq(): void
{
    Auth::cere();
    Auth::cereCsrf();

    // Formularul trimite toate intrebarile deodata. Cele cu text gol se sterg.
    $intrebari = $_POST['faq'] ?? [];
    foreach ($intrebari as $id => $rand) {
        $intrebare = trim((string) ($rand['intrebare'] ?? ''));
        $raspuns   = trim((string) ($rand['raspuns'] ?? ''));
        $ordine    = (int) ($rand['ordine'] ?? 0);
        $afisare   = ($rand['afisare'] ?? 'toate') === 'acasa' ? 'acasa' : 'toate';
        $activ     = isset($rand['activ']) ? 1 : 0;

        if (str_starts_with((string) $id, 'nou')) {
            if ($intrebare !== '' && $raspuns !== '') {
                Database::run(
                    'INSERT INTO faq (intrebare, raspuns, ordine, afisare, activ) VALUES (?, ?, ?, ?, ?)',
                    [$intrebare, $raspuns, $ordine, $afisare, $activ]
                );
            }
        } elseif ($intrebare === '' && $raspuns === '') {
            Database::run('DELETE FROM faq WHERE id = ?', [(int) $id]);
        } else {
            Database::run(
                'UPDATE faq SET intrebare = ?, raspuns = ?, ordine = ?, afisare = ?, activ = ? WHERE id = ?',
                [$intrebare, $raspuns, $ordine, $afisare, $activ, (int) $id]
            );
        }
    }
    admin_flash('Întrebări salvate.');
    redirect('/admin/faq');
}

function admin_resurse(): void
{
    Auth::cere();
    admin_render('resurse', [
        'titlu' => 'Resurse',
        'resurse' => Resursa::adminToate(),
    ]);
}

function admin_salveaza_resurse(): void
{
    Auth::cere();
    Auth::cereCsrf();

    $resurse = $_POST['resurse'] ?? [];
    foreach ($resurse as $id => $rand) {
        $titlu     = trim((string) ($rand['titlu'] ?? ''));
        $descriere = trim((string) ($rand['descriere'] ?? ''));
        $pret      = (float) str_replace(',', '.', (string) ($rand['pret'] ?? '0'));
        $ordine    = (int) ($rand['ordine'] ?? 0);
        $activ     = isset($rand['activ']) ? 1 : 0;

        if (str_starts_with((string) $id, 'nou')) {
            if ($titlu !== '') {
                Database::run(
                    'INSERT INTO resurse (titlu, descriere, pret, ordine, activ) VALUES (?, ?, ?, ?, ?)',
                    [$titlu, $descriere, $pret, $ordine, $activ]
                );
            }
        } elseif ($titlu === '') {
            Database::run('DELETE FROM resurse WHERE id = ?', [(int) $id]);
        } else {
            Database::run(
                'UPDATE resurse SET titlu = ?, descriere = ?, pret = ?, ordine = ?, activ = ? WHERE id = ?',
                [$titlu, $descriere, $pret, $ordine, $activ, (int) $id]
            );
        }
    }
    admin_flash('Resurse salvate.');
    redirect('/admin/resurse');
}

// ===========================================================================
// Mesaje
// ===========================================================================

function admin_mesaje(): void
{
    Auth::cere();
    admin_render('mesaje', [
        'titlu' => 'Mesaje',
        'mesaje' => Mesaj::toate(),
    ]);
}

function admin_sterge_mesaj(): void
{
    Auth::cere();
    Auth::cereCsrf();

    $id = (int) ($_POST['id'] ?? 0);
    Mesaj::sterge($id);
    admin_flash('Mesaj șters. Poate fi recuperat 30 de zile.');
    redirect('/admin/mesaje');
}

// ===========================================================================
// Export manual
// ===========================================================================

function admin_exporta_markdown(): void
{
    Auth::cere();
    Auth::cereCsrf();

    $nr = Export::toate();
    admin_flash("Am exportat {$nr} articole în content-export/.");
    redirect('/admin');
}
