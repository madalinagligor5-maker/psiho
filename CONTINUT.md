# CONTINUT.md — ce fotografii și texte sunt de completat

Site-ul e gata tehnic, dar câteva lucruri nu pot fi inventate — le pui tu.
Sunt de două feluri: **fotografii** (mai jos) și **marcaje `[COMPLETEZ EU]`** din
cod (lista la final).

---

## Fotografii

### Regula de brand: fără portrete

Prin decizia clientei, **nu apar chipuri**. Prezența umană se construiește din
detalii și din voce. Toate fotografiile sunt de detaliu, nu de portret.

> **De confirmat cu clienta:** fiind două psiholoage (nu una), poate fi utilă o
> poză de echipă discretă — mâini, un birou comun, fără fețe. Dacă se acceptă,
> pagina Echipa poate primi o astfel de imagine. Dacă nu, rămâne regula strictă.

### Lista de fotografii

Toate în format **WebP**, cu `width` și `height` explicite. Dimensiuni țintă:

| Unde | Ce | Dimensiune | Nume fișier sugerat |
|---|---|---|---|
| Echipa (opțional) | Poză de echipă discretă, fără fețe: mâini, birou comun, o cană, un caiet | 1200 × 800 | `echipa.webp` |
| Cabinet (Contact/Echipa) | Spațiul cabinetului: un colț cu lumină naturală, fotolii, o plantă | 1200 × 800 | `cabinet.webp` |
| Contact | Hartă statică cu locația (NU Google Maps embed) — o captură din OpenStreetMap | 1200 × 600 | `harta.webp` |
| Articole (copertă) | Detalii: hârtie, textură, un caiet, lumină — se încarcă din admin, per articol | 1200 × 675 | (din admin) |
| Open Graph | O imagine pentru previzualizarea pe rețele (poate fi sigla + un detaliu) | 1200 × 630 | `og.webp` |

Fotografiile se pun în `public_html/assets/images/`, în afară de coperțile de
articol, care se încarcă din admin (se redimensionează și devin WebP automat).

### Ton fotografic

Cald, lumină naturală, detalii de cabinet și de birou — hârtie, țesătură, o cană,
o plantă. **Nu wellness de stoc** (fără femei care meditează pe stânci, fără mâini
cu plante suculente, fără apusuri). Se leagă de identitatea botanică de pe
`@terapie.cu.cristina`.

---

## Sigla

Tema vizuală (culori, linework botanic, fonturi) e deja adoptată de pe Instagramul
Cristinei. Rămâne o singură decizie: monograma „CA" e personală (inițialele
Cristinei). Ori se generalizează la un simbol botanic fără inițiale (linework de
frunze, neutru pentru două persoane), ori rămâne personală și cabinetul primește o
siglă derivată separată. **De decis cu clienta.** Până atunci, site-ul folosește
ramura botanică desenată în cod (`src/views/public/_botanic.php`) ca semnătură.

---

## Marcaje `[COMPLETEZ EU]` din cod

Caută-le cu: `grep -rn "COMPLETEZ EU" src/ seed.php`

Cele mai importante, de completat cu date reale (NU inventate):

**Date de contact** (din admin → Setări, sau în `seed.php`):
- Adresa cabinetului, telefon, program
- Prețuri per serviciu (individual, online, familie, evaluări, fișe, orientare carieră)

**Biografii** (din admin → nu încă; deocamdată în `seed.php`, tabela `psihologi`):
- **Adam Nicoleta-Cristina:** formarea e confirmată; de verificat doar ortografia
  exactă a institutului de psihoterapie („Areopagus"?) cu diploma.
- **Babotan Amalia-Alexandra:** formarea universitară, masteratul, cursurile —
  NU au fost transmise. Nu completa cu presupuneri.

**Pagini legale:** data ultimei actualizări, CUI, detalii SMTP.

**Decizie deschisă — testimoniale:** codul deontologic COPSI le restricționează.
Homepage-ul e proiectat să meargă și cu, și fără un bloc de testimoniale — nu se
adaugă până nu confirmi.
