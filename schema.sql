-- ===========================================================================
-- Schema bazei de date.
--
-- Se importa o singura data, la instalare, din phpMyAdmin sau din linia de
-- comanda. Vezi INSTALARE.md pasul 4.
--
-- Colatie: utf8mb4_unicode_ci peste tot. utf8mb4 (nu utf8) pentru ca "utf8"
-- din MySQL stocheaza maximum 3 octeti pe caracter si taie emoji si o parte
-- din semnele tipografice. Diacriticele romanesti merg si pe utf8, dar nu are
-- rost sa lasam o capcana in urma.
-- ===========================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------------
-- Utilizatori. Doar clienta. Nu exista inregistrare publica.
-- ---------------------------------------------------------------------------
-- Cele doua psiholoage sunt admini EGALI — `rol` exista pentru claritate si
-- pentru o eventuala extindere, dar nu introduce ierarhie in acest build.
-- `psiholog_id` leaga contul de biografia publica, ca articolele scrise de acest
-- cont sa fie atribuite automat persoanei potrivite.
CREATE TABLE IF NOT EXISTS utilizatori (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email           VARCHAR(190) NOT NULL,
    parola_hash     VARCHAR(255) NOT NULL,
    nume            VARCHAR(120) NOT NULL,
    rol             ENUM('admin') NOT NULL DEFAULT 'admin',
    psiholog_id     INT UNSIGNED NULL,
    creat_la        DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ultima_autentificare DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_utilizatori_email (email),
    KEY idx_utilizatori_psiholog (psiholog_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Psihologii cabinetului. O tabela separata de `utilizatori`: aici sta
-- biografia PUBLICA (nume, cod CPR, abordare), acolo sta contul de acces.
-- Le tinem separate ca un cont sters sa nu stearga si biografia afisata public.
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS psihologi (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nume         VARCHAR(120) NOT NULL,
    slug         VARCHAR(120) NOT NULL,
    titlu_scurt  VARCHAR(160) NOT NULL DEFAULT '',   -- ex. „Psiholog clinician"
    cod_cpr      VARCHAR(20)  NOT NULL DEFAULT '',    -- cod personal Colegiul Psihologilor
    judet        VARCHAR(60)  NOT NULL DEFAULT '',
    filiala      VARCHAR(60)  NOT NULL DEFAULT '',
    bio          MEDIUMTEXT   NOT NULL,               -- Markdown
    foto         VARCHAR(255) NULL,
    ordine       SMALLINT     NOT NULL DEFAULT 0,
    activ        TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    UNIQUE KEY uq_psihologi_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Specializarile fiecarui psiholog, cu NIVELUL si REGIMUL de practica.
-- O tabela proprie pentru ca o persoana are mai multe specializari, fiecare cu
-- regimul ei — de exemplu psihologie clinica in regim autonom SI psihoterapie
-- de familie in regim de supervizare. Regimul se afiseaza corect si onest:
-- „autonom" vs „sub supervizare", conform statutului real din registrul COPSI.
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS psihologi_specializari (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    psiholog_id  INT UNSIGNED NOT NULL,
    nume         VARCHAR(120) NOT NULL,               -- ex. „Psihologie clinica"
    nivel        VARCHAR(60)  NOT NULL DEFAULT '',    -- ex. „Practicant"
    regim        ENUM('autonom','supervizare') NOT NULL DEFAULT 'supervizare',
    ordine       SMALLINT     NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_spec_psiholog (psiholog_id),
    CONSTRAINT fk_spec_psiholog FOREIGN KEY (psiholog_id)
        REFERENCES psihologi (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Incercari de autentificare. Sta la baza limitarii de rata si a blocarii.
-- Se curata automat la fiecare autentificare reusita.
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS login_attempts (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    ip          VARBINARY(16) NOT NULL,   -- binar: acopera si IPv6, si e mai mic
    email       VARCHAR(190) NOT NULL,
    reusita     TINYINT(1)   NOT NULL DEFAULT 0,
    incercat_la DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_attempts_ip_timp (ip, incercat_la),
    KEY idx_attempts_email_timp (email, incercat_la)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Categorii de articole.
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS categorii (
    id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nume      VARCHAR(80)  NOT NULL,
    slug      VARCHAR(80)  NOT NULL,
    descriere VARCHAR(255) NULL,
    ordine    SMALLINT     NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    UNIQUE KEY uq_categorii_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Articole.
--
-- `continut` pastreaza Markdown, nu HTML. Editorul TipTap serializeaza in
-- Markdown la salvare. Motivul e in ARHITECTURA.md: Markdown se muta oriunde,
-- HTML-ul produs de un editor se muta greu.
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS articole (
    id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titlu            VARCHAR(200) NOT NULL,
    slug             VARCHAR(200) NOT NULL,
    categorie_id     INT UNSIGNED NULL,
    autor_id         INT UNSIGNED NULL,   -- care psihologa a scris articolul
    rezumat          VARCHAR(400) NOT NULL DEFAULT '',
    continut         MEDIUMTEXT   NOT NULL,
    imagine          VARCHAR(255) NULL,
    imagine_alt      VARCHAR(255) NULL,
    meta_descriere   VARCHAR(180) NOT NULL DEFAULT '',
    stare            ENUM('ciorna','publicat') NOT NULL DEFAULT 'ciorna',
    publicat_la      DATETIME     NULL,
    creat_la         DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizat_la    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_articole_slug (slug),
    KEY idx_articole_stare_data (stare, publicat_la),
    KEY idx_articole_categorie (categorie_id),
    KEY idx_articole_autor (autor_id),
    CONSTRAINT fk_articole_categorie FOREIGN KEY (categorie_id)
        REFERENCES categorii (id) ON DELETE SET NULL,
    CONSTRAINT fk_articole_autor FOREIGN KEY (autor_id)
        REFERENCES psihologi (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Setari editabile din admin: preturi, durate, texte scurte, date de contact.
-- Perechi cheie-valoare, ca sa nu fie nevoie de ALTER TABLE cand apare un camp
-- nou. `tip` spune adminului ce fel de camp sa afiseze.
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS setari (
    cheie     VARCHAR(80)  NOT NULL,
    valoare   TEXT         NOT NULL,
    eticheta  VARCHAR(160) NOT NULL,
    tip       ENUM('text','textarea','numar') NOT NULL DEFAULT 'text',
    grup      VARCHAR(60)  NOT NULL DEFAULT 'general',
    ordine    SMALLINT     NOT NULL DEFAULT 0,
    PRIMARY KEY (cheie)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Intrebari frecvente.
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS faq (
    id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    intrebare VARCHAR(255) NOT NULL,
    raspuns   TEXT         NOT NULL,
    ordine    SMALLINT     NOT NULL DEFAULT 0,
    -- 'acasa' apare si pe prima pagina, 'toate' doar in lista completa.
    afisare   ENUM('acasa','toate') NOT NULL DEFAULT 'toate',
    activ     TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    KEY idx_faq_ordine (activ, ordine)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Mesaje din formularul de contact.
--
-- Continutul e criptat la rest cu AES-256-GCM (vezi src/models/Mesaj.php).
-- Cheia sta in config.php, care nu ajunge in git si nu se sincronizeaza cu
-- rsync. Motivul: mesajele pot contine date de sanatate, categorie speciala
-- in sensul Art. 9 GDPR.
--
-- `sters_la` face stergerea reversibila 30 de zile, apoi curatarea o duce.
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS mesaje_contact (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nume_cif      TEXT         NOT NULL,   -- criptat
    contact_cif   TEXT         NOT NULL,   -- criptat (email sau telefon)
    situatie      VARCHAR(80)  NOT NULL DEFAULT '',
    -- Preferinta de psiholog NU e data sensibila (nu spune nimic despre starea
    -- persoanei), deci se pastreaza in clar, ca sa se poata filtra mesajele.
    psiholog_preferat_id INT UNSIGNED NULL,
    mesaj_cif     TEXT         NULL,       -- criptat
    citit         TINYINT(1)   NOT NULL DEFAULT 0,
    primit_la     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    sters_la      DATETIME     NULL,
    PRIMARY KEY (id),
    KEY idx_mesaje_primit (sters_la, primit_la),
    KEY idx_mesaje_psiholog (psiholog_preferat_id),
    CONSTRAINT fk_mesaje_psiholog FOREIGN KEY (psiholog_preferat_id)
        REFERENCES psihologi (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- Resurse: produsele digitale afisate pe pagina /resurse.
-- In acest build se cer prin email — nu exista checkout.
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS resurse (
    id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titlu     VARCHAR(160) NOT NULL,
    descriere TEXT         NOT NULL,
    pret      DECIMAL(8,2) NOT NULL DEFAULT 0,
    imagine   VARCHAR(255) NULL,
    ordine    SMALLINT     NOT NULL DEFAULT 0,
    activ     TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===========================================================================
-- Tabele pentru magazin — GOALE SI NEFOLOSITE in acest build.
--
-- Exista de acum pentru ca peste sase luni, cand se adauga vanzarea de produse
-- digitale, forma datelor sa fie deja decisa. Un ALTER TABLE gandit acum e mai
-- ieftin decat unul improvizat atunci.
--
-- NU exista niciun cod de plata in acest proiect. Nu scrie in aceste tabele.
-- ===========================================================================

CREATE TABLE IF NOT EXISTS produse (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titlu       VARCHAR(160) NOT NULL,
    slug        VARCHAR(160) NOT NULL,
    descriere   TEXT         NOT NULL,
    pret        DECIMAL(8,2) NOT NULL DEFAULT 0,
    moneda      CHAR(3)      NOT NULL DEFAULT 'RON',
    fisier      VARCHAR(255) NULL,
    activ       TINYINT(1)   NOT NULL DEFAULT 0,
    creat_la    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_produse_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS comenzi (
    id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    numar          VARCHAR(24)  NOT NULL,
    email_cif      TEXT         NOT NULL,
    nume_cif       TEXT         NOT NULL,
    total          DECIMAL(10,2) NOT NULL DEFAULT 0,
    moneda         CHAR(3)      NOT NULL DEFAULT 'RON',
    stare          ENUM('noua','platita','anulata','rambursata') NOT NULL DEFAULT 'noua',
    referinta_plata VARCHAR(120) NULL,
    creat_la       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_comenzi_numar (numar)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS comenzi_items (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    comanda_id  INT UNSIGNED NOT NULL,
    produs_id   INT UNSIGNED NULL,
    titlu       VARCHAR(160) NOT NULL,   -- copiat la momentul comenzii
    pret        DECIMAL(8,2) NOT NULL DEFAULT 0,
    cantitate   SMALLINT     NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    KEY idx_items_comanda (comanda_id),
    CONSTRAINT fk_items_comanda FOREIGN KEY (comanda_id)
        REFERENCES comenzi (id) ON DELETE CASCADE,
    CONSTRAINT fk_items_produs FOREIGN KEY (produs_id)
        REFERENCES produse (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
