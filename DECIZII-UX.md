# DECIZII-UX.md — deciziile de UX și de ce

Lista deciziilor luate, cu motivul fiecăreia. Audiența e specifică: vizitatorul e
adesea anxios, epuizat sau deprimat, cu capacitate de decizie redusă. Aproape
fiecare alegere pornește de aici.

---

## Structură și navigație

1. **Un singur CTA principal, peste tot: „Programează o ședință de cunoaștere".**
   Gratuită, 20 de minute. Niciodată două CTA-uri concurente în același viewport.
   *De ce:* o minte obosită nu vrea să aleagă între opțiuni. Un singur pas clar,
   repetat, e mai ușor de urmat.

2. **Prețuri afișate public, de la început.** Pe Acasă și pe Servicii.
   *De ce:* cercetarea pe site-urile de psihologie din România a arătat că prețul
   ascuns e cea mai mare sursă de fricțiune. A fi nevoit să întrebi e, pentru
   mulți, încă un motiv de amânat.

3. **Pagina „Cum funcționează" răspunde la întrebările pe care nimeni nu le
   răspunde:** ce se întâmplă la prima ședință minut cu minut, cât costă, ce e
   terapia sistemică, online vs. cabinet, ce faci dacă nu se potrivește, ce se
   întâmplă cu ce spui acolo, și — specific cabinetului — cum alegi între cele
   două psiholoage și cum schimbi dacă vrei.
   *De ce:* necunoscutul hrănește anxietatea. Fiecare gol umplut scade un motiv de
   a nu veni.

4. **Servicii pe două grupe, nu o listă plată.** Terapie și sprijin emoțional
   (ton cald) separat de evaluare și documentație (ton instituțional, orientat pe
   proces).
   *De ce:* publicul e diferit — adultul anxios pentru prima grupă, părinți și
   familii care au nevoie de acte pentru a doua. Același ton peste ambele ar suna
   fals pentru una dintre ele.

## Formularul de contact

5. **Maximum 4 câmpuri:** nume, contact (email sau telefon), preferință de
   psiholog (opțional), mesaj (opțional).
   *De ce:* fiecare câmp în plus e un motiv de abandon. Doar numele și contactul
   sunt obligatorii — restul e opțional.

6. **Preferința de psiholog înlocuiește „unde ești acum".** Câmp opțional, cu „Nu
   am o preferință" ca implicit.
   *De ce:* la un cabinet cu două psiholoage, a ști cu cine ar vrea să discute
   vizitatorul e mai acționabil decât o întrebare de deschidere. Am păstrat cele
   4 câmpuri tăind pe cel mai puțin util, nu adăugând un al cincilea.

7. **Fără reCAPTCHA.** Protecție anti-spam prin câmp-capcană (honeypot) + verificare
   de timp.
   *De ce:* reCAPTCHA e un transfer de date către Google, nepotrivit pe un site de
   sănătate, și adaugă fricțiune (bifează semafoarele) exact pentru cine e obosit.

## Ton și limbaj

8. **Fără limbaj patologizant.** Fără „suferi de", fără „tulburare" în titluri.
   Situațiile din „ai ajuns aici pentru că…" sunt la persoana a doua, recognoscibile,
   fără etichete clinice.
   *De ce:* cine se recunoaște într-o descriere caldă face primul pas mai ușor
   decât cine se vede pus într-o categorie de diagnostic.

9. **Fără promisiuni de vindecare** sau rezultate garantate. Regimul de supervizare
   al psiholoagelor e afișat corect și onest, fără accent negativ.
   *De ce:* onestitatea construiește încrederea de care depinde relația terapeutică.

10. **Notă de criză, discretă dar prezentă,** pe Contact și pe Cum funcționează,
    cu 112 și numărul liniei de prevenire a suicidului.
    *De ce:* site-ul nu e un canal de intervenție. Cine e în criză trebuie îndrumat
    imediat către ajutorul potrivit.

## Mișcare

11. **Mișcare calmă, niciodată agitată.** Aparițiile la scroll sunt un fade blând,
    o singură dată; ramura botanică se leagănă foarte lent; cardurile se ridică
    ușor la hover. Nimic nu sare, nimic nu zboară, nimic nu se repetă la infinit.
    *De ce:* audiența e anxioasă. Mișcarea bruscă sau insistentă creează exact
    senzația de agitație de care fug. Versiunea inițială a fost complet statică;
    la cererea clientei am adăugat viață, dar de tip „respirație", nu „spectacol".

12. **`prefers-reduced-motion` respectat prin înlocuire, nu prin dezactivare.**
    Cine cere mai puțină mișcare primește conținutul vizibil din start (fără fade
    care l-ar putea lăsa cu secțiuni goale) și ramura statică.
    *De ce:* a scoate tot feedbackul face interfața mai greu de folosit, nu mai
    ușoară.

## WhatsApp

13. **Buton WhatsApp flotant, dar în paleta site-ului** (bleu, colț dreapta-jos,
    dimensiune moderată), nu verde fluorescent.
    *De ce:* un buton care sparge designul strigă „widget", nu „scrie-mi". Discret,
    dar prezent.

## Accesibilitate ca UX

14. **Font de corp desenat pentru citire grea** (Atkinson Hyperlegible Next, cu
    litere dezambiguizate), corp de 18px, line-height 1.7, paragrafe scurte.
    *De ce:* pentru o minte cu capacitate de procesare redusă, lizibilitatea nu e
    un detaliu de accesibilitate, e o alegere de fond.

15. **Toate perechile de culori verificate WCAG 2.1 AA**, inel de focus vizibil
    peste tot, navigare completă cu tastatura.
    *De ce:* un site pe teme de sănătate trebuie să funcționeze pentru toată lumea,
    inclusiv pentru cine folosește tehnologii asistive.
