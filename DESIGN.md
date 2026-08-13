---
typography:
  scale:
    micut: 0.75rem
    nota: 0.8125rem
    mic: 0.9375rem
    baza: 1rem
    corpMobil: 1.0625rem
    corp: 1.125rem
    corpMare: 1.25rem
    h3: 1.375rem
    titlu: 1.5rem
    h2: 1.75rem
    h1: 2.5rem
  corp:
    fontFamily: "Atkinson Hyperlegible Next"
    fontSize: 1.125rem
    lineHeight: 1.7
  display:
    fontFamily: "Source Serif 4"
    fontSize: clamp(2rem, 1.4rem + 2.4vw, 3.25rem)
  # Titlul de hero e o treaptă mai mare, fluidă — prezență mai puternică pe
  # prima pagină și pe capetele de secțiune, fără a împinge restul scării.
  hero:
    fontFamily: "Source Serif 4"
    fontSize: clamp(2.4rem, 1.5rem + 3.4vw, 3.75rem)
  # Marca din antet, scrisă de mână — semnătura caldă a cabinetului. Caveat (OFL),
  # self-hostat, subset la latine + diacritice RO. Folosit DOAR pentru nume.
  marca:
    fontFamily: "Caveat"
    fontSize: 1.5rem
colors:
  # PALETA OFICIALA a clientei (coduri hex furnizate în kitul de ilustrație).
  # Rece, soft, aerisită. Pale = suprafețe/decor; închise = text/interactiv.
  # Toate perechile folosite verificate WCAG 2.1 AA.
  cerneala: "#20323A"       # text principal — „Cerneală"
  cernealaMoale: "#33474F"
  piatra: "#5B6E77"         # text secundar — „Cerneală blândă"
  salvie: "#BFD7C1"         # verde salvie — accent/umplutură decorativă
  salvieInchis: "#3E6152"   # verde închis — linkuri, linework, focus
  salvieMediu: "#345244"
  lavanda: "#C9BEE3"        # lavandă — accent decorativ (nou)
  lavandaPal: "#EDE9F5"     # lavandă pal — suprafață
  lavandaText: "#5E4E86"    # lavandă lizibilă ca text
  azur: "#E3F0F5"           # azur pal — suprafață
  verdePal: "#E6F0E5"       # verde pal — suprafață
  bleu: "#2E5A6B"           # azur închis — butoane, eyebrow, accente (alb 7.5)
  bleuClar: "#24485A"
  bleuPal: "#E3F0F5"        # azur pal — panoul CTA, subsolul
  teracota: "#C9BEE3"       # alias -> lavandă (compat cu cod vechi)
  teracotaText: "#5E4E86"   # alias -> lavandă text
  crem: "#F6FAFB"           # fundalul paginii — „Ceață" (rece; nume istoric)
  greige: "#E6F0E5"         # suprafață secundară — „Verde pal"
  caramida: "#8C3A2B"
  alb: "#FFFFFF"
  # Rampa de neutre CALDE pentru suprafata de admin ("Operate"). Partea publica
  # foloseste doar crem/greige; panoul are nevoie de mai multe trepte pentru
  # borduri, fundaluri de tabel si stari de hover, toate trase spre cald.
  n50: "#F3EFE8"
  n100: "#EFE9DF"
  n200: "#E4DCCE"
  n300: "#D6CDBC"
  n400: "#B3A896"
  n500: "#8F8676"
  # Tonuri pentru textul de pe bara laterala inchisa (fundal = cerneala).
  inkHover: "#3A362F"
  onInk: "#DDD6C9"
  onInkDim: "#A79E8F"
  # Spalari palide ale semanticelor, pentru fundalul etichetelor de stare.
  bineTint: "#E6ECE7"
  atentieTint: "#F1E7DD"
  rauTint: "#F3E3E0"
rounded:
  none: 0
  mic: 2px
  mediu: 4px
  pastila: 999px
---

# Planul de design

Scris înainte de orice CSS, conform §4 din brief. Revizuit o dată — ce s-a
schimbat la revizuire e notat la final.

> **⚠ Actualizare — pivot „Adam & Babotan" + cererea clientei.** Sursa de
> adevăr pentru CULORI e frontmatter-ul YAML de sus (deja actualizat), NU tabelul
> §1 de mai jos, care descrie paleta inițială (verde pădure / ocru) și e păstrat
> doar ca istoric. Paleta curentă e caldă — crem, salvie, teracotă — extrasă din
> @terapie.cu.cristina, plus un **bleu deschis** (#3A6699 / #E4EDF6) adăugat ca
> ancoră rece, modernă (butoane, blocul CTA, subsol, eyebrow). Elementul-semnătură
> e acum ramura botanică (§4, rescris), nu „Firul". Politica de mișcare e
> relaxată la „mișcare calmă" (§5 în PRODUCT.md, rescris) — apariții blânde la
> scroll, legănarea botanică, ridicarea cardurilor la hover; totul dezactivat la
> `prefers-reduced-motion`. Toate perechile noi de culori sunt verificate WCAG.

---

## 1. Paleta — 6 valori de bază

| Token | Hex | Rol |
|---|---|---|
| `--cerneala` | `#16211C` | Verde-negru. Tot corpul de text și titlurile. |
| `--brad` | `#2C5545` | Verde de brad. Linkuri în text, fundalul CTA-ului principal. |
| `--ocru` | `#B07D2B` | Ocru. **Material grafic, nu text.** Firul, sublinierile, marcajele. |
| `--hartie` | `#EDEEE7` | Fundalul. Hârtie la lumină de zi — răcită, nu crem. |
| `--hartie-umbra` | `#E0E2D8` | A doua suprafață: casete, inserturi, rândurile de FAQ. |
| `--piatra` | `#56615B` | Text secundar: meta, legende, date. |

Derivate: `--brad-clar #3D6B58` (hover), `--ocru-inchis #8A5F1C` (ocru când chiar
trebuie să fie text), `--caramida #8C3A2B` (eroare și nota de criză — nicăieri altundeva).

### Contraste verificate

Toate cele 13 perechi folosite efectiv trec WCAG 2.1 AA. Calculate, nu estimate:

```
cerneala / hartie        14.18:1     corp de text
cerneala / hartie-umbra  12.65:1     corp pe suprafața 2
brad / hartie             7.22:1     linkuri
alb / brad                8.43:1     text pe CTA
caramida / hartie         6.53:1     eroare, nota de criză
piatra / hartie           5.52:1     text secundar
ocru-inchis / hartie      4.82:1     ocru ca text
piatra / hartie-umbra     4.93:1     text secundar pe suprafața 2
ocru / hartie             3.09:1     firul, marcaje — non-text, prag 3.0
```

Cele mai strânse sunt `piatra / hartie-umbra` (4.93) și `ocru-inchis / hartie`
(4.82). Ambele trec, dar nu mai coborî luminozitatea hârtiei-umbră fără să
recalculezi.

## 2. Tipografia

**Display: Source Serif 4** (variabil, axe `opsz` + `wght`).

Motivație: e un serif *de text* cu contrast scăzut, nu un Didone. Playfair,
Cormorant și DM Serif — alegerile implicite pentru „site de terapie" — au
contrast mare între plin și subțire, ceea ce semnalează modă și lux. Un serif de
carte semnalează pagină tipărită. Se leagă de ancorarea în caiet și hârtie din
brief, și nu strigă. Axa `opsz` se leagă la dimensiunea reală a titlului, deci
titlurile mari primesc automat forme mai fine, iar cele mici rămân robuste.

**Corp: Atkinson Hyperlegible Next** (variabil, axă `wght`).

Motivație: desenat de Braille Institute pentru citire cu vedere slabă. Literele
sunt dezambiguizate deliberat — I/l/1, O/0, majuscula I. Pentru un vizitator
epuizat sau anxios, cu capacitate de procesare redusă, asta e o alegere de fond,
nu de stil. E și fontul pe care un model generativ nu îl alege implicit.

**De ce merg împreună:** ambele au fost desenate pentru *citit*, nu pentru
aspect — unul pentru citit lung, celălalt pentru citit greu. Perechea are o
logică, nu doar un contrast serif/sans.

**Diacritice:** ambele acoperă ș/ț cu virgulă dedesubt (U+0218/0219, U+021A/021B),
verificat direct în tabela `cmap` a fontului, nu presupus din lista de subseturi.
10 din 10 caractere românești prezente în ambele.

**Reguli fixe:** corp 18px desktop / 17px mobil, `line-height` 1.7, paragrafe de
maximum 3–4 rânduri, măsură de 62 de caractere.

## 3. Layout: „Marginea"

Coloana de text are 62ch și e deplasată la dreapta. Marginea stângă rămâne
permanent liberă și ține trei lucruri: firul, numerele de secțiune, și notele
marginale — recunoașterile la persoana a doua, la un corp mai mic.

Referința e caietul adnotat al unui clinician, nu grila de landing page.
Asimetria e structurală, nu decorativă: marginea are o treabă.

Pe mobil se pliază în coloană unică. Firul devine un semn scurt, de 24px,
înaintea fiecărui titlu de secțiune.

**Evită explicit:** hero centrat urmat de trei carduri. Nu apare nicăieri.

## 4. Elementul-semnătură: ramura botanică

O ramură botanică delicată, în linework, în marginea hero-ului: o tulpină care
se ramifică, cu frunze care alternează stânga/dreapta ca o plantă reală, plus
câteva boabe calde (teracotă) în vârf. Codul: `src/views/public/_botanic.php`.

De ce ăsta: vine direct din identitatea reală a clientei (@terapie.cu.cristina,
unde motivele botanice apar sistematic), și literalizează în același timp „nu
caut vinovatul, caut tiparul" — o tulpină care **se ramifică lateral**, nu doar
crește în sus, exact ideea sistemică. E versiunea botanică a „Firului" din
proiectul inițial: aceeași idee, limbajul clientei.

**Mișcare:** se leagănă foarte lent (7–9.5s, defazat între frunze, ca să nu pară
mecanic) — „respiră". Static la `prefers-reduced-motion`. Apare și, discret, în
colțul blocului CTA (pe fundal bleu pal), ca semnătură caldă recurentă.

Tulpina (linia structurală) e în salvie închis, lizibilă; frunzele umplute sunt
decorative, în salvie și teracotă deschise (exceptate WCAG 1.4.11).

## 5. Politica de mișcare, declarată înapoi

Brief-ul cere să declar diviziunea explicit. O declar:

**Public = Persuade/Read. Nimic nu se mișcă dacă vizitatorul nu a provocat
mișcarea.** Fără reveal la scroll, fără parallax, fără animație de intrare la
încărcare. Permis strict: tranziții pe hover și focus, meniul mobil, feedback de
validare la formular, extinderea acordeonului din FAQ. Durate scurte, fără
bounce, fără overshoot.

**Admin = Operate. Mișcarea e feedback și trebuie să fie bună.** Progres real la
upload, confirmare la salvare draft, tranziție la publicare, undo vizibil la
ștergere. UI optimist unde e sigur.

`prefers-reduced-motion` înlocuiește mișcarea cu opacitate. Nu dezactivează tot.

### Justificarea fiecărei mișcări de pe site-ul public

Sunt patru. Fiecare are un motiv care nu e „arată bine":

1. **Hover/focus pe linkuri și butoane** — 120ms pe culoare. Fără asta,
   elementul interactiv nu se confirmă ca interactiv. Deservește claritatea.
2. **Meniul mobil** — 180ms. Un meniu care apare instant pare o eroare de
   randare. Tranziția spune de unde a venit.
3. **Validarea formularului** — mesajul de eroare apare cu 100ms de opacitate,
   fără deplasare. Deplasarea ar muta câmpurile sub degetul cuiva care tocmai a
   greșit; asta e exact tipul de fricțiune pe care îl evităm.
4. **Acordeonul din FAQ** — 200ms pe înălțime. Fără el, restul paginii sare, iar
   cititorul își pierde locul.

Nimic altceva. Fără excepții.

---

## 6. Ce am schimbat la revizuire

Brief-ul cere ca planul să fie revizuit și ca partea generică să fie numită.
Trei lucruri erau ce aș fi produs pentru orice site de terapie:

**1. Fundalul era crem cald.** Prima variantă era `#F2F1EA` — R > G > B, adică
exact cremul cald pe care îl semnalează brief-ul ca aspect implicit al
designului generat de AI. Schimbat în `#EDEEE7`, unde **G > R**. Deplasarea e de
patru puncte pe un canal, dar mută materialul din „crem de nuntă" în „hârtie la
lumină de nord". Asta e mișcarea de temperatură pe care o cere §4.

**2. Ocrul era accent de text.** Prima variantă îl folosea pentru subtitluri și
numere — ceea ce, pe un fond cald, e literalmente terracotta-pe-crem, șablonul
numit în brief. Acum ocrul e **material grafic**: firul, sublinierile, marcajele.
Contrastul lui pe hârtie e 3.09:1 — suficient pentru grafică, insuficient pentru
text. Restricția se autoimpune, nu depinde de disciplina mea.

**3. Terracotta a dispărut complet.** Nu există în paletă. Cărămida apare doar
la mesaje de eroare și la nota despre criza acută, adică exact acolo unde roșul
înseamnă ceva.

Al patrulea lucru pe care l-am schimbat, deși nu era generic ci doar slab:
**firul era un timeline.** Un timeline vertical e un clișeu de pagină „cum
funcționează". Ramificația laterală către notele marginale e ce îl face să
însemne altceva — legături în lateral, nu doar succesiune în jos.

---

## 7. Ce nu apare în acest site

Listă de verificare înainte de fiecare commit:

- Gradiente violet, sau orice gradient pe suprafețe mari
- `backdrop-filter`, glassmorphism, umbre grele
- Fotografie de stoc „wellness"
- Portret, poză de profil, sau orice layout care presupune un chip
- Hero centrat + trei carduri
- Registrul „deblochează-ți potențialul"
- „Suferi de", „tulburare" în titluri, orice limbaj patologizant
- Promisiuni de vindecare sau rezultate garantate
- Inter, Roboto, Plus Jakarta Sans, Space Grotesk, Geist, Fraunces
- Easing cu bounce sau elastic
- Două CTA-uri concurente în același viewport
- Secțiune de testimoniale — **până la confirmare explicită**
