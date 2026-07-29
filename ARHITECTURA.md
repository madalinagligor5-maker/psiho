# ARHITECTURA.md — harta proiectului

Scris pentru cineva care nu a mai văzut acest cod de opt luni (sau deloc). Explică
ce trăiește unde și, mai important, **de ce**.

---

## Ideea în trei fraze

Aplicație PHP simplă, fără framework: un front controller trimite fiecare cerere
către un router, care cheamă un controller, care randează un șablon. Conținutul
stă în MySQL, dar fiecare articol se exportă și ca fișier Markdown (ieșirea de
siguranță). Zero dependențe PHP; singura unealtă externă (editorul TipTap) se
compilează local și rezultatul se comite.

## Structura de foldere

```
public_html/          ← RĂDĂCINA WEB. Doar ce e aici e accesibil din browser.
  index.php           ← front controller: toate cererile intră aici (via .htaccess)
  .htaccess           ← rutare, HTTPS, cache, blocări
  500.html            ← pagină de eroare statică, autonomă
  assets/             ← css, js (bundle), fonturi woff2, imagini, favicon
  uploads/            ← imaginile încărcate din admin (webp, fără EXIF)

src/                  ← DEASUPRA rădăcinii web. Codul, inaccesibil din browser.
  Router.php          ← potrivire de rute pe tipare {parametru}
  Database.php        ← înveliș subțire peste PDO (interogări pregătite)
  Auth.php            ← autentificare, rate limit, CSRF
  Markdown.php        ← convertor Markdown→HTML scris de mână (~200 linii)
  Export.php          ← scrie articolele ca .md (ieșirea de siguranță)
  Imagine.php         ← resize + WebP + strip EXIF la upload
  helpers.php         ← config(), e(), url(), asset(), slugify(), setare(), smtp
  models/             ← Articol, Faq, Resursa, Mesaj, Psiholog
  controllers/        ← public.php (pagini publice), admin.php (CMS)
  views/
    layout/           ← pagina.php (public), banner-cookies.php
    public/           ← acasa, echipa, servicii, cum-functioneaza, contact, ...
    admin/            ← tablou, editor, mesaje, setari, faq, resurse, ...

config/               ← DEASUPRA rădăcinii web.
  config.example.php  ← model (se comite)
  config.php          ← real, cu parole (GITIGNORED, nu se urcă prin deploy)

content-export/       ← fișierele .md generate la publicare (ieșirea de siguranță)
schema.sql            ← structura bazei de date (14 tabele)
seed.php              ← datele inițiale + conturile de admin (rulat o dată)
deploy.sh             ← sincronizare pe server prin rsync

assets-src/           ← DOAR DEZVOLTARE. Sursa editorului (admin.mjs).
package.json          ← DOAR DEZVOLTARE. esbuild + TipTap, pentru a construi
node_modules/           bundle-ul. NIMIC de aici nu ajunge pe server.
```

**Regula de aur a structurii:** `src/` și `config/` stau **deasupra** lui
`public_html/`. Dacă ajung înăuntru, oricine le poate citi din browser — inclusiv
parolele. `index.php` le caută cu `dirname(__DIR__)`, adică exact un nivel mai sus.

## Cum curge o cerere

1. `.htaccess` trimite tot ce nu e fișier real la `public_html/index.php`.
2. `index.php` încarcă helpers + clasele, pornește sesiunea, setează antete de
   securitate, apoi `$router->dispatch()`.
3. Routerul potrivește calea cu un tipar și cheamă funcția-handler din
   `controllers/public.php` sau `admin.php`.
4. Handlerul cere date din `models/`, apoi `render('public/pagina', [...])`.
5. `render()` pune conținutul șablonului în layout și îl trimite la browser.

## Decizii care par ciudate până le înțelegi

**De ce Markdown propriu, nu o bibliotecă?** Nu randăm Markdown de pe internet, ci
exact setul de noduri pe care le produce editorul TipTap. Set închis și mic → 200
de linii ajung, iar proiectul rămâne cu zero dependențe PHP.

**De ce `src/views/` și nu un motor de template?** Șabloanele sunt PHP simplu cu
`<?= e($x) ?>`. Un motor de template ar fi o dependență în plus pentru zero câștig
la o aplicație de mărimea asta.

**De ce psihologii sunt o tabelă separată de utilizatori?** `utilizatori` = conturi
de acces; `psihologi` = biografia publică. Un cont șters nu trebuie să șteargă și
biografia afișată pe site.

**De ce preferința de psiholog NU e criptată, dar mesajul e?** Mesajul poate
conține date de sănătate (categorie specială GDPR) → criptat AES-256-GCM.
Preferința („vreau să discut cu X") nu spune nimic despre starea persoanei și
trebuie să poată fi filtrată → text clar.

**De ce tabelele produse/comenzi există goale?** Ca forma datelor pentru viitorul
magazin să fie deja decisă. Un `ALTER TABLE` gândit acum e mai ieftin decât unul
improvizat peste șase luni. **Nu există cod de plată.**

## Capcane de reținut

- **Detectorul Impeccable** (unealta de design): punctul de intrare e
  `scripts/detector/detect-antipatterns.mjs`, NU `cli/main.mjs` (acela iese tăcut
  cu 0). Pe fișiere `.php` sare — treci CSS-ul prin el, sau conținutul prin stdin.
- **Bundle-ul editorului** (`public_html/assets/js/admin.js`) se CONSTRUIEȘTE local
  cu `npm run build` și se COMITE. Serverul nu are pas de build.
- **`config.php`** nu e în git și nu se urcă prin `deploy.sh`. Se creează o dată,
  manual, pe server.
