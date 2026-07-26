<?php
declare(strict_types=1);

/**
 * Populeaza baza de date cu datele initiale.
 *
 * Se ruleaza o singura data, dupa importul schema.sql, din SSH:
 *   php seed.php
 *
 * Sau, daca nu ai SSH, prin cron o data la 1 minut si apoi stergi cron-ul.
 * Scriptul e idempotent: rulat de doua ori nu dubleaza nimic.
 *
 * Creeaza si primul utilizator de admin. Parola se cere de la tastatura, ca
 * sa nu ramana scrisa in fisier sau in istoricul shell-ului.
 */

require __DIR__ . '/src/helpers.php';
require __DIR__ . '/src/Database.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('seed.php se ruleaza doar din linia de comanda.');
}

echo "Populez baza de date...\n\n";

// ---------------------------------------------------------------------------
// Categorii
// ---------------------------------------------------------------------------

$categorii = [
    ['Anxietate',           'anxietate',           'Neliniște, atacuri de panică, gânduri care nu se opresc.', 1],
    ['Depresie',            'depresie',            'Lipsa de energie, de sens, de bucurie.', 2],
    ['Burnout',             'burnout',             'Epuizare legată de muncă și de rolurile pe care le duci.', 3],
    ['Tipare relaționale',  'tipare-relationale',  'Ce se repetă în felul în care te raportezi la ceilalți.', 4],
];

foreach ($categorii as [$nume, $slug, $descriere, $ordine]) {
    Database::run(
        'INSERT INTO categorii (nume, slug, descriere, ordine) VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE nume = VALUES(nume), descriere = VALUES(descriere), ordine = VALUES(ordine)',
        [$nume, $slug, $descriere, $ordine]
    );
}
echo "  categorii: " . count($categorii) . "\n";

// ---------------------------------------------------------------------------
// Psihologi
//
// Datele de acreditare (cod CPR, judet, filiala, specializari, regim) sunt
// REALE si verificate pe verificapsiholog.ro la 25 iulie 2026. Nu le
// „imbunatati" retoric si nu inventa simetrie intre cele doua.
//
// Regula de acuratete, obligatorie: nicaieri nu se sugereaza ca vreuna
// practica autonom pe o specializare aflata in regim de supervizare.
// ---------------------------------------------------------------------------

$psihologi = [
    [
        'nume'        => 'Adam Nicoleta-Cristina',
        'titlu_scurt' => 'Psiholog clinician · psihoterapeut în formare',
        'cod_cpr'     => '26689',
        'judet'       => 'Caraș-Severin',
        'filiala'     => 'Timiș',
        'ordine'      => 1,
        // Formare confirmata de clienta (WhatsApp, 16.07.2026). Numele
        // institutului de psihoterapie ramane marcat pana la confirmarea vizuala
        // a diplomei (clienta a scris „Aropagus", probabil „Areopagus").
        'bio' => <<<'MD'
Sunt psiholog clinician, acreditată de Colegiul Psihologilor din România. Lucrez cu oameni care trec prin anxietate, depresie sau epuizare, dar și cu familii și cupluri — pentru că de multe ori ceea ce doare la o persoană se înțelege abia privind relațiile din jurul ei.

## Formare

- Licență în Psihologie — Universitatea de Vest din Timișoara
- Masterat în Psihologie Clinică și Psihoterapie — Universitatea de Vest din Timișoara
- Psihoterapeut în formare în psihoterapie sistemică și de cuplu, la [COMPLETEZ EU: Areopagus — confirmă ortografia exactă cu diploma]
- Formare în Cercul Siguranței (Circle of Security), program de parenting bazat pe teoria atașamentului

## Cum lucrez

Nu caut vinovatul, caut tiparul. Te privesc în context — în familie, în cuplu, în rolurile pe care le-ai preluat — nu ca pe un simptom izolat. Sunt directă, dar nu grăbită: ritmul îl dai tu.
MD,
        'specializari' => [
            ['Psihologie clinică', 'Practicant', 'autonom', 1],
            ['Psihoterapie de familie', 'Practicant', 'supervizare', 2],
        ],
    ],
    [
        'nume'        => 'Babotan Amalia-Alexandra',
        'titlu_scurt' => 'Psiholog clinician, sub supervizare',
        'cod_cpr'     => '27188',
        'judet'       => 'Satu Mare',
        'filiala'     => 'Satu Mare',
        'ordine'      => 2,
        // Formarea si serviciile pentru Babotan NU au fost confirmate inca.
        // Nu presupune simetrie cu Adam; totul ramane [COMPLETEZ EU].
        'bio' => <<<'MD'
Sunt psiholog clinician, acreditată de Colegiul Psihologilor din România, și practic în regim de supervizare — sub îndrumarea unui psiholog specialist. Este statutul normal la nivel de practicant și înseamnă că munca mea e sprijinită de un cadru de verificare, nu că lucrez singură cu cazurile.

## Formare

[COMPLETEZ EU: formarea universitară, masteratul și eventualele cursuri de formare continuă ale Amaliei — nu au fost încă transmise. Nu completez cu presupuneri.]

## Cum lucrez

[COMPLETEZ EU: câteva rânduri în vocea Amaliei despre abordarea ei — la fel ca la Cristina, e paragraful care nu poate fi scris în locul ei.]
MD,
        'specializari' => [
            ['Psihologie clinică', 'Practicant', 'supervizare', 1],
        ],
    ],
];

foreach ($psihologi as $p) {
    $slug = slugify($p['nume']);
    $existent = Database::value('SELECT id FROM psihologi WHERE slug = ?', [$slug]);
    if ($existent !== null) {
        $psihologId = (int) $existent;
    } else {
        $psihologId = Database::insert(
            'INSERT INTO psihologi (nume, slug, titlu_scurt, cod_cpr, judet, filiala, bio, ordine, activ)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)',
            [$p['nume'], $slug, $p['titlu_scurt'], $p['cod_cpr'], $p['judet'], $p['filiala'], $p['bio'], $p['ordine']]
        );
        foreach ($p['specializari'] as [$specNume, $nivel, $regim, $ord]) {
            Database::run(
                'INSERT INTO psihologi_specializari (psiholog_id, nume, nivel, regim, ordine)
                 VALUES (?, ?, ?, ?, ?)',
                [$psihologId, $specNume, $nivel, $regim, $ord]
            );
        }
    }
}
echo "  psihologi: " . count($psihologi) . "\n";

// ---------------------------------------------------------------------------
// Setari
//
// Tot ce apare mai jos poate fi schimbat din admin, fara dezvoltator.
// Preturile in special: ea nu trebuie sa ceara nimanui sa schimbe un pret.
// ---------------------------------------------------------------------------

$setari = [
    // grup: preturi
    ['pret_individual',      '250',  'Preț ședință individuală (lei)',           'numar',   'preturi', 1],
    ['durata_individual',    '50',   'Durată ședință individuală (minute)',       'numar',   'preturi', 2],
    ['pret_online',          '220',  'Preț ședință online (lei)',                 'numar',   'preturi', 3],
    ['durata_online',        '50',   'Durată ședință online (minute)',            'numar',   'preturi', 4],
    ['durata_cunoastere',    '20',   'Durată ședință de cunoaștere (minute)',     'numar',   'preturi', 5],

    // grup: contact
    ['adresa',               '[COMPLETEZ EU: strada, număr, oraș]', 'Adresa cabinetului', 'text', 'contact', 1],
    ['program',              "Luni – joi, 10:00 – 19:00\nVineri, 10:00 – 15:00", 'Program', 'textarea', 'contact', 2],
    ['timp_raspuns',         'Răspundem în maximum 48 de ore lucrătoare.', 'Timp de răspuns anunțat', 'text', 'contact', 3],

    // grup: texte
    ['titlu_acasa',          'Nu caut vinovatul, caut tiparul.', 'Titlul de pe prima pagină', 'text', 'texte', 1],

    // grup: cabinet — entitatea legala reala (societate civila profesionala)
    ['cabinet_nume',         'Adam și Babotan', 'Numele cabinetului (afișat)', 'text', 'cabinet', 1],
    ['cabinet_entitate',     'ADAM ȘI BABOTAN, Societate civilă profesională de psihologie', 'Denumire legală completă', 'text', 'cabinet', 2],
    ['cabinet_certificat',   'Nr. certificat formă de exercitare: 109 · Filiala Timiș · 16.02.2023', 'Certificat formă de exercitare', 'text', 'cabinet', 3],
];

foreach ($setari as [$cheie, $valoare, $eticheta, $tip, $grup, $ordine]) {
    // Nu suprascrie o valoare pe care clienta a schimbat-o deja din admin.
    Database::run(
        'INSERT INTO setari (cheie, valoare, eticheta, tip, grup, ordine) VALUES (?, ?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE eticheta = VALUES(eticheta), tip = VALUES(tip), grup = VALUES(grup), ordine = VALUES(ordine)',
        [$cheie, $valoare, $eticheta, $tip, $grup, $ordine]
    );
}
echo "  setări: " . count($setari) . "\n";

// ---------------------------------------------------------------------------
// Intrebari frecvente
// ---------------------------------------------------------------------------

$faq = [
    [
        'Cât durează o ședință și cât de des ne vedem?',
        "O ședință durează 50 de minute. La început ne vedem săptămânal — nu pentru că așa e regula, ci pentru că la două săptămâni distanță se pierde firul și fiecare întâlnire începe de la capăt.\n\nDupă câteva luni, dacă lucrurile s-au așezat, putem rări.",
        1, 'acasa',
    ],
    [
        'De unde știu dacă am nevoie de terapie?',
        "Nu există un prag de suferință de la care „se justifică”. Dacă te-ai întrebat, întrebarea în sine e un motiv suficient să vorbim o dată.\n\nȘedința de cunoaștere există exact pentru asta: douăzeci de minute în care afli cum lucrez și eu aflu ce cauți, fără să te angajezi la nimic.",
        2, 'acasa',
    ],
    [
        'Ce se întâmplă cu ce spun acolo?',
        "Rămâne acolo. Confidențialitatea e obligație profesională, nu politețe.\n\nExistă trei excepții, prevăzute de lege: risc iminent pentru viața ta sau a altcuiva, suspiciunea de abuz asupra unui minor, și solicitarea unei instanțe. Ți le spun din prima ședință, nu în treacăt.",
        3, 'acasa',
    ],
    [
        'Online sau la cabinet — care e mai bine?',
        "Depinde mai puțin de preferință decât pare. Online funcționează bine când programul e imprevizibil sau când drumul până la cabinet ar deveni el însuși un motiv de amânare.\n\nLa cabinet funcționează mai bine când ai nevoie de un spațiu care nu e casa ta, sau când acasă nu ai unde vorbi liniștit.\n\nPutem începe într-un fel și schimba pe parcurs.",
        4, 'toate',
    ],
    [
        'Ce fac dacă nu ne potrivim?',
        "Îmi spui, și e în regulă. Nu e un eșec și nu e o problemă de politețe — potrivirea dintre terapeut și om e unul dintre puținele lucruri despre care cercetarea e clară că influențează rezultatul.\n\nDacă simți asta, te ajut să găsești pe altcineva. Nu iau personal.",
        5, 'toate',
    ],
    [
        'Cum plătesc?',
        "La sfârșitul fiecărei ședințe, prin transfer bancar sau în numerar. Pentru ședințele online, transferul se face în ziua ședinței.\n\nDacă anulezi cu mai puțin de 24 de ore înainte, ședința se tarifează — nu ca penalizare, ci pentru că intervalul rămâne blocat și nu mai poate fi dat altcuiva.",
        6, 'toate',
    ],
];

foreach ($faq as [$intrebare, $raspuns, $ordine, $afisare]) {
    $exista = Database::value('SELECT id FROM faq WHERE intrebare = ?', [$intrebare]);
    if ($exista === null) {
        Database::run(
            'INSERT INTO faq (intrebare, raspuns, ordine, afisare, activ) VALUES (?, ?, ?, ?, 1)',
            [$intrebare, $raspuns, $ordine, $afisare]
        );
    }
}
echo "  întrebări frecvente: " . count($faq) . "\n";

// ---------------------------------------------------------------------------
// Articole
// ---------------------------------------------------------------------------

$articole = [
    [
        'titlu'     => 'Anxietatea nu e o defecțiune. E un semnal citit greșit',
        'categorie' => 'anxietate',
        'rezumat'   => 'Corpul tău nu s-a stricat. Face exact ce a învățat să facă. Întrebarea utilă nu e cum să opresc asta, ci ce încearcă să-mi spună.',
        'meta'      => 'Anxietatea ca semnal, nu ca defect. Ce face corpul, de ce, și ce se schimbă când încetezi să te lupți cu el.',
        'continut'  => <<<'MD'
Cei mai mulți oameni care ajung la mine cu anxietate au încercat deja să scape de ea. Au citit, au respirat cum scria în aplicație, s-au certat cu propriile gânduri. Unii au reușit o vreme. Aproape toți s-au întors cu senzația că e ceva stricat la ei.

Nu e nimic stricat.

## Ce face corpul

Anxietatea e un sistem de alarmă care funcționează. Problema nu e că sună — problema e că sună la lucruri care nu mai sunt periculoase, pentru că a învățat cândva că sunt.

Un copil care crește într-o casă unde tonul vocii tatălui prevestea ce urmează învață să citească tonurile. Devine foarte bun la asta. Douăzeci de ani mai târziu, într-o ședință de la birou, cineva își schimbă tonul și corpul lui e deja în alertă înainte ca el să apuce să se gândească.

Nu e o exagerare. E o competență veche, aplicată într-un context nou.

## De ce lupta cu ea o face mai mare

Când tratezi anxietatea ca pe un dușman, adaugi un al doilea strat: teama de teamă. Îți urmărești bătăile inimii ca să prinzi din timp momentul. Eviți situațiile în care „s-ar putea”. Fiecare evitare confirmă sistemului că avea dreptate să sune.

Asta e mecanismul prin care o anxietate ținută sub control ani de zile ajunge, treptat, să conducă tot mai mult din viața cuiva. Nu prin intensitate. Prin teritoriu.

## Întrebarea pe care o pun în loc

Nu întreb *cum oprim asta*. Întreb **când a fost util**.

Aproape întotdeauna a fost. Aproape întotdeauna a fost util într-un sistem — o familie, o relație, un loc de muncă — unde vigilența era singura formă de siguranță disponibilă. Anxietatea a fost o soluție. A rămas pe loc după ce contextul s-a schimbat.

Asta schimbă complet natura muncii. Nu demontăm un defect. Actualizăm o hartă.

## Ce se schimbă concret

În primele luni, majoritatea oamenilor nu raportează că simt mai puțină anxietate. Raportează că li se pare mai puțin urgentă. Că se trezesc noaptea și nu mai intră imediat în panică din cauza faptului că s-au trezit.

Intensitatea scade după. Aproape niciodată înainte.

---

*Dacă ceva de aici ți-a sunat cunoscut, o ședință de cunoaștere durează douăzeci de minute și nu te obligă la nimic.*
MD,
    ],
    [
        'titlu'     => 'Burnout nu înseamnă că ai muncit prea mult',
        'categorie' => 'burnout',
        'rezumat'   => 'Oamenii care ajung la epuizare rareori au muncit cel mai mult. Au muncit cel mai mult fără să poată spune nu — și diferența asta contează.',
        'meta'      => 'De ce burnout-ul nu se rezolvă cu concediu, și ce întrebare merită pusă în locul întrebării despre volumul de muncă.',
        'continut'  => <<<'MD'
Prima dată când cineva îmi spune „cred că am burnout”, urmează aproape mereu o justificare. Câte ore, câte proiecte, de câți ani. Ca și cum ar trebui să demonstreze că a meritat.

Volumul contează mai puțin decât pare.

## Ce arată de fapt epuizarea

Oamenii care ajung la epuizare rareori sunt cei care au muncit cel mai mult. Sunt cei care au muncit cel mai mult **fără să poată spune nu**.

Diferența e importantă. Un om care lucrează șaizeci de ore pe săptămână la ceva ales, cu posibilitatea reală de a se opri, obosește. Un om care lucrează patruzeci de ore într-un loc unde a refuza înseamnă un cost pe care nu și-l permite, se golește.

Nu e aceeași stare, și nu se repară cu același lucru.

## De ce concediul nu ajunge

De asta două săptămâni de concediu nu rezolvă nimic. Te întorci odihnit într-un sistem nemodificat, și în zece zile ești unde erai. Mulți oameni interpretează asta ca pe o dovadă că problema e la ei — că nici măcar odihna nu îi mai repară.

Nu odihna a eșuat. Odihna nu era răspunsul la întrebarea corectă.

## Unde mă uit în schimb

Nu la orarul tău. La ce se întâmplă în jurul tău când te oprești.

- Cine preia ce lași?
- Ce se strică dacă nu te ocupi tu?
- Cine te-a învățat că e treaba ta să nu se strice?

Aproape întotdeauna, în spatele epuizării stă un rol pe care cineva l-a preluat devreme și pe care nimeni nu i l-a mai luat înapoi. Copilul care ținea atmosfera în casă. Cel care rezolva. Cel pe care „te puteai baza”.

Rolul nu s-a schimbat. Doar s-a mutat la birou.

## Ce facem cu asta

Terapia sistemică nu începe prin a-ți spune să pui limite. Începe prin a înțelege ce cost real are, în sistemele din care faci parte, punerea unei limite. Pentru că dacă acel cost e mare și nediscutat, orice sfat despre limite e o formă politicoasă de a-ți spune că nu te străduiești destul.

Iar tu te-ai străduit deja destul. De aceea ești obosit.

---

*Dacă recunoști ceva aici, putem vorbi douăzeci de minute, fără angajament.*
MD,
    ],
    [
        'titlu'     => 'De ce se repetă aceleași certuri, cu oameni diferiți',
        'categorie' => 'tipare-relationale',
        'rezumat'   => 'A treia relație în care se întâmplă exact același lucru nu e ghinion. E un tipar — și tiparele se pot vedea, ceea ce le face să se poată schimba.',
        'meta'      => 'Ce sunt tiparele relaționale, de ce se repetă cu parteneri diferiți, și cum arată munca sistemică pe ele.',
        'continut'  => <<<'MD'
Cineva îmi povestește a treia relație. Alt om, alt oraș, altă vârstă. Aceeași ceartă.

Momentul în care își dă seama singur, în timp ce vorbește, e aproape întotdeauna același: se oprește la mijlocul propoziției.

## Ce e un tipar

Un tipar nu e o trăsătură de caracter. E o secvență: ce faci tu, ce face celălalt ca răspuns, ce faci tu ca răspuns la răspuns. Buclă închisă, care se autoîntreține.

Cel mai frecvent din câte văd arată așa: unul se apropie când e neliniștit, celălalt se retrage când e presat. Apropierea produce retragere. Retragerea produce mai multă apropiere. Amândoi fac exact lucrul care declanșează ce se tem cel mai tare.

Niciunul nu e vinovat. Ambii sunt în buclă.

## De ce se schimbă partenerul dar nu și cearta

Pentru că nu alegi partenerul la întâmplare. Alegi, fără să vrei, pe cineva a cărui mișcare completează mișcarea ta. Cineva care se retrage e greu de observat pentru un om care nu se apropie. Devine vizibil — și important — exact pentru cel care se apropie.

Nu e ghinion. E potrivire de formă.

## De ce nu caut vinovatul

Pentru că nu există unul, și pentru că întrebarea „cine a început” nu a rezolvat niciodată o buclă. Fiecare are dreptate din interiorul propriei poziții. Cel care se apropie are dreptate că celălalt se retrage. Cel care se retrage are dreptate că celălalt presează.

Ambele afirmații sunt adevărate. De asta cearta e nesfârșită.

Terapia sistemică se uită la **secvență**, nu la persoane. Întrebarea nu e cine a greșit, ci ce urmează după ce.

## Ce se schimbă când vezi tiparul

Mai puțin decât speră oamenii, și mai mult decât cred.

Nu dispare. Prima schimbare reală e că îl recunoști în timp ce se întâmplă, nu la trei zile după. Sună mic. Nu e: un tipar recunoscut la mijloc se poate întrerupe. Unul recunoscut la sfârșit se poate doar regreta.

A doua schimbare e că încetezi să-l citești ca pe o dovadă despre cine ești tu sau cine e celălalt. Devine ceva ce faceți împreună, nu ceva ce sunteți.

De acolo se poate lucra.

---

*Lucrez cu oameni individual pe tipare relaționale — nu e nevoie să vină și partenerul.*
MD,
    ],
];

// Cele doua autoare, dupa ordine, ca sa atribuim articolele alternativ.
$autori = Database::all('SELECT id FROM psihologi ORDER BY ordine, id');

$inserate = 0;
foreach ($articole as $i => $a) {
    $slug = slugify($a['titlu']);
    if (Database::value('SELECT id FROM articole WHERE slug = ?', [$slug]) !== null) {
        continue;
    }
    $categorieId = Database::value('SELECT id FROM categorii WHERE slug = ?', [$a['categorie']]);
    // Atribuire alternativa intre cele doua psiholoage.
    $autorId = $autori[$i % max(1, count($autori))]['id'] ?? null;

    Database::run(
        'INSERT INTO articole
            (titlu, slug, categorie_id, autor_id, rezumat, continut, meta_descriere, stare, publicat_la)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [
            $a['titlu'], $slug, $categorieId, $autorId, $a['rezumat'], $a['continut'], $a['meta'],
            'publicat',
            // Datate la cateva saptamani distanta, ca lista sa arate a site viu.
            date('Y-m-d H:i:s', strtotime("-" . (($i + 1) * 18) . " days")),
        ]
    );
    $inserate++;
}
echo "  articole: {$inserate}\n";

// ---------------------------------------------------------------------------
// Resurse (produse digitale, cerute prin email — fara checkout)
// ---------------------------------------------------------------------------

$resurse = [
    ['Jurnal de tipare — 30 de zile',
     "Un caiet de lucru pentru observarea propriilor reacții, câte o pagină pe zi. Nu e un jurnal liber: fiecare pagină are trei întrebări scurte, gândite ca să scoată la iveală secvența, nu emoția.\n\nSe folosește cel mai bine în paralel cu terapia, dar funcționează și singur.",
     0, 1],
    ['Ghid: primele trei luni după ce ai început terapia',
     "Ce e normal să simți, ce nu e, și de ce a treia lună e cea în care cei mai mulți oameni se opresc — exact înainte de partea care ajută.",
     0, 2],
];

foreach ($resurse as [$titlu, $descriere, $pret, $ordine]) {
    $exista = Database::value('SELECT id FROM resurse WHERE titlu = ?', [$titlu]);
    if ($exista === null) {
        Database::run(
            'INSERT INTO resurse (titlu, descriere, pret, ordine, activ) VALUES (?, ?, ?, ?, 1)',
            [$titlu, $descriere, $pret, $ordine]
        );
    }
}
echo "  resurse: " . count($resurse) . "\n";

// ---------------------------------------------------------------------------
// Conturile de admin — cate unul pentru fiecare psihologa, egale ca drepturi.
//
// Fiecare cont e legat de biografia publica (psiholog_id), ca articolele scrise
// din acel cont sa fie atribuite automat persoanei potrivite.
// ---------------------------------------------------------------------------

$nrUtilizatori = (int) Database::value('SELECT COUNT(*) FROM utilizatori');

if ($nrUtilizatori > 0) {
    echo "\nExistă deja utilizatori. Nu creez altele.\n";
} else {
    echo "\nCreez conturile de admin, câte unul pentru fiecare psihologă.\n";
    $listaPsihologi = Database::all('SELECT id, nume FROM psihologi ORDER BY ordine, id');

    // Citim tot prin fgets(STDIN), nu prin readline(): e mai portabil (extensia
    // readline lipseste pe unele configurari CLI de gazduire partajata) si se
    // comporta previzibil si cand intrarea vine dintr-un pipe.
    $citeste = static function (string $prompt): string {
        echo $prompt;
        return trim((string) fgets(STDIN));
    };

    foreach ($listaPsihologi as $ps) {
        echo "\n  Cont pentru {$ps['nume']}:\n";
        $email = $citeste('    Email: ');

        @shell_exec('stty -echo 2>/dev/null');
        $parola = $citeste('    Parolă (minimum 12 caractere): ');
        @shell_exec('stty echo 2>/dev/null');
        echo "\n";

        if (mb_strlen($parola) < 12) {
            echo "    Parolă prea scurtă. Sar peste acest cont — rulează din nou pentru el.\n";
            continue;
        }

        Database::run(
            'INSERT INTO utilizatori (email, parola_hash, nume, rol, psiholog_id) VALUES (?, ?, ?, "admin", ?)',
            [$email, password_hash($parola, PASSWORD_DEFAULT), $ps['nume'], $ps['id']]
        );
        echo "    Cont creat pentru {$ps['nume']}.\n";
    }
    echo "\n  Vă puteți autentifica la /admin/autentificare\n";
}

echo "\nGata.\n";
