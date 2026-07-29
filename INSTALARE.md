# INSTALARE.md — pași exacți pentru cPanel

Ghid de la zero pentru a pune site-ul pe o găzduire românească cu cPanel
(SiteBunker, Hostico și altele). Scris pentru cineva care nu a mai instalat
niciodată o aplicație PHP.

---

## 0. Cel mai important lucru — structura de foldere

Aplicația **nu** se pune „tot în `public_html`". Codul (`src/`, `config/`) trebuie
să stea **DEASUPRA** rădăcinii web, ca nimeni de pe internet să nu poată citi
fișierele cu parole și cu logica site-ului.

Pe server, structura corectă arată așa:

```
/home/CONTUL_TAU/            ← directorul tău personal (home)
├── src/                     ← AICI, deasupra lui public_html
├── config/
│   └── config.php           ← îl creezi tu (nu vine din arhivă)
├── content-export/          ← se creează singur la prima publicare
└── public_html/             ← RĂDĂCINA WEB — doar conținutul folderului
    ├── index.php               public_html din proiect
    ├── .htaccess
    ├── assets/
    └── uploads/
```

Greșeala tipică (și cea pe care probabil ai făcut-o): ai urcat **tot folderul
proiectului** în `public_html`, așa că a ajuns `public_html/public_html/…` și
`public_html/src/…`. De aceea browserul arată o listă de fișiere („Index of /").

### Cum repari, din cPanel → File Manager

1. Deschide **cPanel → File Manager** și intră în `public_html`.
2. Vei vedea înăuntru folderele urcate greșit: `public_html`, `src`, `config`,
   plus fișiere ca `schema.sql`, `seed.php` etc.
3. Selectează `src`, `config`, `content-export` (dacă există), `schema.sql`,
   `seed.php`, `README`-uri — și apasă **Move**. La destinație, șterge `public_html`
   din cale ca să rămână doar `/home/CONTUL_TAU` (un nivel mai sus).
4. Intră în folderul interior `public_html` (cel din proiect). Selectează **tot**
   ce e în el (`index.php`, `.htaccess`, `assets`, `uploads`) și **Move** în
   `public_html`-ul real (un nivel mai sus).
5. Șterge folderul interior `public_html`, acum gol.
6. Activează afișarea fișierelor ascunse (Settings → Show Hidden Files) și
   verifică faptul că `.htaccess` a ajuns direct în `public_html`.

La final, în `public_html` trebuie să vezi direct `index.php` și `.htaccess`,
iar `src` și `config` să fie **lângă** `public_html`, nu în el.

> Alternativă, dacă găzduirea nu îți permite să muți fișiere deasupra lui
> `public_html`: din **cPanel → Domains**, schimbă „Document Root"-ul domeniului
> să pointeze către `public_html/public_html`. Are același efect — codul rămâne
> în afara rădăcinii web. Prima variantă e totuși cea curată.

---

## 1. Versiunea de PHP

Din **cPanel → MultiPHP Manager**, selectează domeniul și pune-l pe
**PHP 8.2** (sau 8.3). Sub 8.2 site-ul nu pornește — folosește sintaxă nouă.

Verifică și că sunt active extensiile (de obicei sunt, implicit): `pdo_mysql`,
`gd`, `mbstring`, `openssl`, `fileinfo`, `exif`. Le găsești în
**cPanel → Select PHP Version → Extensions**.

---

## 2. Baza de date

1. **cPanel → MySQL® Databases**.
2. La „Create New Database", scrie un nume (ex. `psiholog`). Numele final va avea
   prefixul contului: `contul_tau_psiholog`. Notează-l exact.
3. La „Add New User", creează un utilizator și o parolă puternică (folosește
   generatorul cPanel). Notează-le.
4. La „Add User To Database", adaugă utilizatorul la baza de date și bifează
   **ALL PRIVILEGES**.

---

## 3. Importul schemei

1. **cPanel → phpMyAdmin**.
2. În stânga, selectează baza de date creată.
3. Tab-ul **Import** → alege fișierul `schema.sql` din proiect → **Go**.
4. Ar trebui să vezi 14 tabele create (articole, psihologi, utilizatori etc.).

---

## 4. Fișierul de configurare

`config/config.php` **nu vine în arhivă** (conține parole). Îl creezi din model.

1. În File Manager, intră în `config/` (cel de deasupra lui `public_html`).
2. Copiază `config.example.php` și redenumește copia în `config.php`.
3. Editează `config.php` (click dreapta → Edit) și completează:
   - `db` → `name`, `user`, `pass` cu ce ai notat la pasul 2.
   - `site` → `url` (ex. `https://cabinetul-tau.ro`, fără slash la final), `nume`,
     `email`, `telefon`, `whatsapp`.
   - `site` → `mediu` pune-l pe **`prod`** (foarte important pe server — ascunde
     erorile tehnice de vizitatori).
   - `securitate` → `cheie_criptare`: generează o cheie rulând în
     **cPanel → Terminal** (dacă ai SSH) comanda:
     ```
     php -r "echo bin2hex(random_bytes(32));"
     ```
     Copiază cele 64 de caractere în `cheie_criptare`. **Fă o copie a cheii într-un
     loc sigur** — dacă o pierzi, mesajele deja criptate devin necitibile.
   - `mail` → datele SMTP (vezi pasul 6).

---

## 5. Primele conturi de admin

Cabinetul are **două psiholoage**, deci se creează **două conturi**, egale.

**Dacă ai acces SSH** (cel mai simplu):

```bash
cd ~            # directorul home, unde e seed.php
php seed.php
```

Scriptul cere, pe rând, email + parolă (minimum 12 caractere) pentru fiecare
psihologă. Populează și articolele demo, categoriile și setările.

**Dacă NU ai SSH:** creează conturile manual. Generează întâi hash-ul parolei —
poți folosi un fișier temporar `hash.php` urcat în `public_html`, cu:

```php
<?php echo password_hash('PAROLA_TA_AICI', PASSWORD_DEFAULT);
```

Deschide-l o dată în browser, copiază hash-ul afișat, **apoi șterge imediat
fișierul**. Introdu manual în phpMyAdmin, tabelul `utilizatori`, un rând cu
`email`, `parola_hash` (hash-ul copiat), `nume`, `rol` = `admin` și `psiholog_id`
= id-ul psihologei din tabelul `psihologi`. Repetă pentru a doua.

---

## 6. Email (SMTP)

Ca mesajele din formularul de contact să ajungă pe email (nu în spam):

1. **cPanel → Email Accounts** → creează o adresă, ex. `contact@cabinetul-tau.ro`.
2. În `config.php`, la `mail`, completează:
   - `host`: de obicei `mail.cabinetul-tau.ro`
   - `port`: `465`, `securizat`: `ssl` (sau `587` / `tls`)
   - `user` și `pass`: adresa creată și parola ei
   - `expeditor`: aceeași adresă
   - `destinatar`: unde vrei să primești notificările

---

## 7. SSL (HTTPS)

1. **cPanel → SSL/TLS Status** (sau „Let's Encrypt™ SSL").
2. Bifează domeniul și `www`, apasă **Run AutoSSL** (sau „Issue").
3. Așteaptă câteva minute. `.htaccess`-ul forțează deja HTTPS, iar regula pentru
   `/.well-known/` lasă validarea certificatului să treacă.
4. **După** ce confirmi că `https://` merge pe tot site-ul, poți activa HSTS:
   decomentează linia `Strict-Transport-Security` din `public_html/.htaccess`.

---

## 8. Verificare finală

- Deschide `https://cabinetul-tau.ro` — trebuie să apară pagina de acasă.
- `https://cabinetul-tau.ro/admin/autentificare` — intră cu un cont creat.
- Trimite-ți un mesaj de test din formularul de contact și verifică în
  **Admin → Mesaje** că a ajuns.
- Verifică `https://cabinetul-tau.ro/sitemap.xml` și `/robots.txt`.

---

## 9. Permisiuni foldere

Din File Manager, asigură-te că `public_html/uploads/` are permisiuni `755`
(click dreapta → Change Permissions). Aici se salvează imaginile încărcate.

---

## 10. Curățenie de securitate

- Șterge de pe server orice `hash.php` temporar folosit la pasul 5.
- Confirmă că `config/config.php` **nu** e accesibil din browser: încearcă
  `https://cabinetul-tau.ro/../config/config.php` — trebuie să dea eroare, nu să
  afișeze conținut. (Dacă ai pus `src` și `config` deasupra lui `public_html`,
  cum trebuie, nu sunt accesibile deloc.)
- `seed.php` și `schema.sql` fiind deasupra rădăcinii web, nu sunt accesibile
  public. Dacă din vreun motiv le-ai lăsat în `public_html`, șterge-le.
