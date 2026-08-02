<?php
/**
 * Sablon de configurare.
 *
 * La instalare: copiaza acest fisier in config/config.php si completeaza
 * valorile. config.php e in .gitignore si NU ajunge niciodata in git.
 *
 * Vezi INSTALARE.md pentru pasii exacti in cPanel.
 */

return [

    // --- Baza de date -----------------------------------------------------
    // In cPanel, numele bazei si al utilizatorului primesc automat prefixul
    // contului, de forma "numecont_". Copiaza-le exact cum apar acolo.
    'db' => [
        'host'    => 'localhost',
        'name'    => 'numecont_psiholog',
        'user'    => 'numecont_psiholog',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],

    // --- Site -------------------------------------------------------------
    'site' => [
        // Fara slash la final. Trebuie sa fie https in productie.
        'url'      => 'https://exemplu.ro',
        'nume'     => '[COMPLETEZ EU: nume complet]',
        'titlu'    => '[COMPLETEZ EU: nume] — psiholog clinician',
        'email'    => 'terapiecunico@gmail.com',
        'telefon'  => '[COMPLETEZ EU: telefon in format +40...]',
        'whatsapp' => '[COMPLETEZ EU: numar WhatsApp, doar cifre, ex 40712345678]',

        // 'dev' arata erorile pe ecran. 'prod' le scrie doar in log.
        // Pe serverul live trebuie sa fie 'prod'.
        'mediu'    => 'prod',
    ],

    // --- Securitate -------------------------------------------------------
    'securitate' => [
        // Cheie de 32 de octeti, in hex (64 de caractere), pentru criptarea
        // mesajelor de contact la rest. Genereaza una noua cu:
        //   php -r "echo bin2hex(random_bytes(32));"
        // Daca o pierzi, mesajele deja criptate devin necitibile. Fa o copie.
        'cheie_criptare' => '',

        // Dupa cate incercari esuate se blocheaza autentificarea, si pe cat timp.
        'max_incercari'  => 5,
        'durata_blocare' => 900, // secunde (15 minute)
    ],

    // --- Trimitere email --------------------------------------------------
    // SMTP de la gazduire. Nu folosi mail() — ajunge in spam.
    'mail' => [
        'host'      => 'mail.exemplu.ro',
        'port'      => 465,
        'user'      => 'contact@exemplu.ro',
        'pass'      => '',
        'securizat' => 'ssl',  // 'ssl' pentru portul 465, 'tls' pentru 587
        'expeditor' => 'contact@exemplu.ro',
        // Unde ajung notificarile de la formularul de contact.
        'destinatar' => '[COMPLETEZ EU: adresa unde vrei sa primesti mesajele]',
    ],

    // --- Analiza trafic ---------------------------------------------------
    // Plausible sau Matomo self-hosted. NU Google Analytics: transfera date
    // catre SUA si nu se impaca cu Art. 9 din GDPR pe un site de sanatate.
    // Lasa 'domeniu' gol ca sa dezactivezi complet analiza.
    'analiza' => [
        'furnizor' => 'plausible',
        'domeniu'  => '',
        'script'   => 'https://plausible.io/js/script.js',
    ],

    // --- Retentie date ----------------------------------------------------
    // Dupa cate zile se sterg automat mesajele din formularul de contact.
    // Politica de confidentialitate afiseaza aceasta valoare — daca o schimbi
    // aici, se schimba si acolo.
    'retentie_mesaje_zile' => 365,
];
