# PRODUCT.md

Brief-ul de produs. Fiecare comandă Impeccable citește acest fișier.

---

## Ce este

Site de prezentare pentru un **cabinet asociat de psihologie** din Timișoara,
format din **două psiholoage** sub aceeași formă de exercitare:
**ADAM ȘI BABOTAN, Societate civilă profesională de psihologie** (certificat 109,
Filiala Timiș, 16.02.2023).

- **Adam Nicoleta-Cristina** — CPR 26689. Psihologie clinică (regim autonom),
  psihoterapie de familie (sub supervizare). Plus evaluări, fișe CES/grad,
  orientare în carieră.
- **Babotan Amalia-Alexandra** — CPR 27188. Psihologie clinică (sub supervizare).
  Restul detaliilor: neconfirmate încă, marcate `[COMPLETEZ EU]`.

Nișa de comunicare: **anxietate, depresie, burnout, tipare relaționale**. Ședințe
individuale la cabinet și online, plus servicii de evaluare și documentație.

**Regulă de acuratețe, obligatorie:** niciun text nu sugerează practică autonomă
pe o specializare aflată în regim de supervizare. „Sub supervizare" se afișează
corect, fără accent negativ — e statutul normal la nivel de practicant.

Produse digitale (jurnale, ghiduri) — planificate, **nu în acest build**.
Fără checkout, fără cod de plată.

## Unghiul de comunicare

> **„Nu caut vinovatul, caut tiparul."**

Terapia sistemică nu tratează un simptom izolat. Se uită la relațiile dintre o
persoană și sistemele din care face parte: familie, cuplu, muncă.

Site-ul trebuie să transmită asta **fără jargon clinic**. Fără „suferi de",
fără „tulburare" în titluri, fără promisiuni de vindecare.

## Constrângere de brand

**Fără fotografii de portret**, ca regulă de pornire. Prezența umană se
construiește din detalii — mâini, un jurnal, o cană, spațiul cabinetului,
texturi — plus vocea scrisului.

*De confirmat cu clienta:* fiind două persoane, poate fi utilă o poză de echipă
discretă (mâini, birou comun, fără fețe). Layoutul o suportă, dar nu o presupune.

## Identitatea vizuală

Extrasă din contul real al Cristinei, **@terapie.cu.cristina**: paletă caldă
(crem/blush, salvie, teracotă), linework botanic delicat, ton grafic calm.
Plus, la cererea clientei, un **bleu deschis** ca ancoră rece, modernă. Vezi
DESIGN.md pentru paletă și pentru elementul-semnătură (ramura botanică).

---

## Cele două suprafețe

Acest proiect are două jumătăți cu roluri opuse. Nu lăsa un singur limbaj
vizual să le acopere pe amândouă.

### Site public — mod: Persuade / Read

Vizitatorul e anxios, epuizat sau deprimat. Capacitatea lui de decizie e redusă.

- **Un singur CTA principal peste tot:** „Programează o ședință de cunoaștere"
  (gratuită, 20 de minute). Niciodată două CTA-uri concurente în același viewport.
- **Prețuri afișate public.** Ascunderea prețului e cea mai mare sursă de
  fricțiune pe site-urile de psihologie din România.
- Formular de contact: **maximum 4 câmpuri**.
- Fără popup-uri la intrare. Fără carusele cu autoplay.
- Buton WhatsApp flotant, dar **discret și în paleta site-ului** — nu verde
  fluorescent care sparge designul.

### Panou admin — mod: Operate

Îl folosește singură, fără ajutor de la dezvoltator. Asta e constrângerea de design.

Trebuie să știe fără ambiguitate că imaginea s-a încărcat, că draftul s-a salvat,
că publicarea a reușit, că ștergerea se poate anula.

---

## Politica de mișcare

Cele două jumătăți sunt **opuse**. Aplică diviziunea deliberat.

### Public — mișcarea servește calmul (revizuit)

Versiunea inițială a fost complet statică. La cererea clientei, s-a adăugat
**mișcare calmă, de tip „respirație", nu „spectacol"**. Regula: nimic nu sare,
nimic nu zboară, nimic nu se repetă insistent.

- **Permis:** apariții blânde la scroll (fade + urcare de câțiva pixeli, o
  singură dată), ramura botanică ce se leagănă foarte lent, ridicare ușoară a
  cardurilor la hover, plus tranzițiile de dinainte (hover/focus, meniu mobil,
  validare formular, acordeon FAQ).
- **Interzis:** parallax, bounce, overshoot, animații care se repetă agresiv,
  orice mișcare care agită.
- `prefers-reduced-motion` înlocuiește mișcarea cu opacitate; conținutul e
  vizibil din start, ramura stă nemișcată.

### Admin — mișcarea e feedback, și trebuie să fie bună

Schimbările de stare primesc tranziții reale. UI optimist unde e sigur.
Jumătatea asta trebuie să pară o unealtă modernă, pentru că o folosește
săptămânal, iar încrederea în unealtă e ce o ține publicând.

### `prefers-reduced-motion`

Respectat corect: **nu prin dezactivarea a tot**, ci prin înlocuirea mișcării
cu opacitate.

---

## Anti-referințe — ce NU trebuie să iasă

- **Fotografie de stoc „wellness".** Fără femei care meditează pe stânci, fără
  mâini care țin plante suculente, fără apusuri.
- **Gradiente violet.** Niciun `linear-gradient(135deg, #667eea, #764ba2)`.
- **Registrul „deblochează-ți potențialul".** Fără limbaj de coaching
  motivațional. Fără „transformă-ți viața".
- **Crem cald + serif de contrast mare + accent terracotta.** Acesta este
  aspectul implicit al designului generat de AI în acest moment. Se va citi ca
  șablon. Direcția verde/ocru e a clientei — păstrează substanța, dar găsește
  versiunea specifică, nu pe cea generică.
- **Hero centrat urmat de trei carduri.**
- **Glassmorphism**, umbre grele, `backdrop-filter`.
- **Limbaj patologizant.** Fără „suferi de", fără „tulburare" în titluri.
- **Promisiuni de vindecare** sau rezultate garantate.

## Ancorare vizuală

Cabinet de terapie, un caiet, textura hârtiei și a țesăturii, lumină naturală.
**Nu wellness de stoc.**

---

## Stack

PHP 8.2+ și MySQL/MariaDB pe găzduire partajată românească cu cPanel.
**Fără pas de build pe server.** CSS scris de mână. Fonturi self-hostate woff2.
Fără dependențe CDN pentru nimic critic — din motive GDPR cât și de performanță.

Sub 15 dependențe directe, versiuni fixate.

## Reguli de conținut

- **Nu inventa** acreditări, ani de experiență, testimoniale sau statistici.
  Unde lipsește un detaliu, lasă un marcaj vizibil `[COMPLETEZ EU: ...]`.
- Tot conținutul către utilizator e **în română**. Comentariile din cod sunt în
  română. Numele de variabile și funcții rămân în engleză.
- Corp de text minimum 18px desktop, 17px mobil, line-height 1.7.
- Paragrafe de maximum 3–4 rânduri.

## Întrebare deschisă, nerezolvată

**Testimoniale.** Cercetarea competiției le recomandă, dar codul deontologic al
psihologilor din România restricționează folosirea testimonialelor de la clienți.

**Nu construi o secțiune de testimoniale până la confirmare.** Pagina de acasă e
proiectată astfel încât un bloc de semnal de încredere să poată fi adăugat sau
omis fără să strice layoutul.
