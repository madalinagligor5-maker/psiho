<?php
/**
 * Cum functioneaza — pagina cea mai importanta a site-ului (§5).
 * Exista ca sa raspunda la intrebarile pe care niciun site concurent nu le
 * raspunde. Firul se vede cel mai clar aici.
 */
$durata = setare('durata_cunoastere', '20');
?>

<section class="sectiune">
  <p class="eticheta">Cum funcționează</p>
  <h1>Ce se întâmplă, mai exact, dacă vii</h1>
  <p class="introducere" style="margin-top: var(--s3)">
    Cred că e mai ușor să faci primul pas când știi ce urmează. Așa că aici e
    tot: cum decurge o ședință, cât costă, ce e terapia sistemică, și ce se
    întâmplă cu ceea ce spui în cabinet.
  </p>
</section>

<!-- Prima sedinta, minut cu minut -->
<section class="sectiune">
  <p class="eticheta">Prima ședință</p>
  <h2>Cum arată, minut cu minut</h2>
  <span class="nota-margine">
    Nu trebuie să vii pregătit. Nu există răspunsuri bune sau greșite, și nu
    trebuie să știi de unde să începi.
  </span>

  <ol class="pasi" style="margin-top: var(--s4)">
    <li class="pas">
      <h3>Primele minute</h3>
      <p class="fara-margine-jos">Ne facem cunoștință. Îți spun cum lucrez, cât durează, cum decurge. Nu începem cu „spune-mi despre copilăria ta” — începem cu tine, azi.</p>
    </li>
    <li class="pas">
      <h3>Ce te-a adus</h3>
      <p class="fara-margine-jos">Îmi povestești, în ritmul tău, ce te-a făcut să cauți pe cineva. Eu ascult și întreb — nu ca să te evaluez, ci ca să înțeleg cum se leagă lucrurile.</p>
    </li>
    <li class="pas">
      <h3>Prima hartă</h3>
      <p class="fara-margine-jos">Spre final, îți spun ce am observat: nu un verdict, ci o primă schiță a tiparului. De multe ori e prima dată când cineva pune lucrurile împreună cu voce tare.</p>
    </li>
    <li class="pas">
      <h3>Ce urmează</h3>
      <p class="fara-margine-jos">Stabilim dacă și cum continuăm. Fără presiune să te hotărăști pe loc — poți să te gândești și să-mi scrii după.</p>
    </li>
  </ol>
</section>

<!-- Durata, cost, plata -->
<section class="sectiune">
  <p class="eticheta">Practic</p>
  <h2>Cât durează, cât costă, cum plătești</h2>
  <p style="margin-top: var(--s3)">
    O ședință durează <?= e(setare('durata_individual', '50')) ?> de minute. La
    început ne vedem de obicei săptămânal — nu din regulă, ci pentru că la două
    săptămâni distanță se pierde firul și fiecare întâlnire reîncepe de la capăt.
    După ce lucrurile se așază, putem rări.
  </p>
  <p>
    Ședința individuală la cabinet costă <?= e(setare('pret_individual', '250')) ?> lei,
    cea online <?= e(setare('pret_online', '220')) ?> lei. Plata se face la
    sfârșitul fiecărei ședințe, prin transfer sau numerar. Pentru online,
    transferul se face în ziua ședinței.
  </p>
  <p>
    Dacă anulezi cu mai puțin de 24 de ore înainte, ședința se tarifează. Nu ca
    pedeapsă — ci pentru că intervalul rămâne blocat și nu mai poate fi dat
    altcuiva care aștepta.
  </p>
</section>

<!-- Ce e terapia sistemica -->
<section class="sectiune">
  <p class="eticheta">Metoda</p>
  <h2>Ce e terapia sistemică și cu ce diferă</h2>
  <span class="nota-margine">
    Nu înseamnă că aduci pe cineva cu tine. Lucrez cu tine individual — sistemul
    e în felul în care privim, nu neapărat în cine e în cameră.
  </span>
  <p style="margin-top: var(--s3)">
    Cele mai cunoscute forme de terapie se concentrează fiecare pe altceva.
    Terapia cognitiv-comportamentală lucrează mai ales cu gândurile și
    comportamentele de acum. Psihanaliza caută rădăcina în trecutul îndepărtat,
    de obicei pe termen lung.
  </p>
  <p>
    Terapia sistemică se uită la <strong>relații și tipare</strong>: nu doar ce
    simți, ci ce se întâmplă între tine și oamenii din jur, ce rol ai preluat în
    familie, ce se repetă. Un simptom nu e privit ca o defecțiune individuală, ci
    ca ceva ce are sens în contextul din care faci parte.
  </p>
  <p>
    De aceea nu caut vinovatul. Într-un tipar care se repetă, întrebarea „cine a
    început” nu a rezolvat niciodată nimic. Întrebarea utilă e „ce urmează după
    ce” — și aceea se poate schimba.
  </p>
</section>

<!-- Online vs cabinet -->
<section class="sectiune">
  <p class="eticheta">Online sau la cabinet</p>
  <h2>Care ți se potrivește</h2>
  <p style="margin-top: var(--s3)">
    Depinde mai puțin de preferință decât pare.
  </p>
  <div class="servicii" style="margin-top: var(--s4)">
    <div class="serviciu">
      <h3>Online se potrivește dacă</h3>
      <p class="fara-margine-jos">Ai un program imprevizibil, locuiești departe, sau drumul până la cabinet ar deveni el însuși un motiv de amânare. Ai nevoie doar de un colț liniștit și de internet.</p>
    </div>
    <div class="serviciu">
      <h3>La cabinet se potrivește dacă</h3>
      <p class="fara-margine-jos">Ai nevoie de un spațiu care nu e casa ta, sau acasă nu ai unde vorbi netulburat. Pentru mulți oameni, faptul că ies din context ajută gândirea.</p>
    </div>
  </div>
  <p style="margin-top: var(--s4)">
    Putem începe într-un fel și schimba pe parcurs. Nu e o alegere definitivă.
  </p>
</section>

<!-- Cum alegi intre cele doua psiholoage -->
<section class="sectiune">
  <p class="eticheta">Cu cine lucrezi</p>
  <h2>Cum alegi psihologul potrivit dintre noi</h2>
  <p style="margin-top: var(--s3)">
    Suntem două psiholoage, iar asta ridică o întrebare firească: cu cine începi?
    De cele mai multe ori, contează mai puțin decât pare — abordarea e comună, iar
    ședința de cunoaștere e exact locul unde se limpezește.
  </p>
  <p>
    Dacă ai o preferință — pentru că ai citit un articol scris de una dintre noi,
    sau pentru o specializare anume — o poți spune direct în formularul de contact.
    Dacă nu, alegem împreună, în funcție de ce cauți: unele lucruri (evaluări, fișe,
    terapie de familie) le oferă doar una dintre noi; vezi
    <a href="<?= e(url('echipa')) ?>">pagina Echipa</a> pentru detalii.
  </p>
  <p>
    <strong>Și dacă vrei să schimbi pe parcurs?</strong> Se poate, și nu e o
    problemă. Îți spui, iar noi ne asigurăm că trecerea se face cu grijă, fără să
    reiei totul de la capăt. Continuitatea îngrijirii e responsabilitatea noastră,
    nu o povară pe care o duci tu.
  </p>
</section>

<!-- Daca nu ne potrivim -->
<section class="sectiune">
  <p class="eticheta">Dacă nu merge</p>
  <h2>Ce faci dacă nu ne potrivim</h2>
  <p style="margin-top: var(--s3)">
    Îmi spui, și e în regulă. Potrivirea dintre om și terapeut e unul dintre
    puținele lucruri despre care cercetarea e clară că influențează rezultatul —
    mai mult decât metoda.
  </p>
  <p>
    Dacă simți că nu e, nu e un eșec și nu trebuie să găsești o scuză politicoasă.
    Te ajut să găsești pe altcineva, dacă vrei. A pleca e o opțiune care rămâne
    mereu pe masă, și faptul că știi asta face de multe ori mai ușor să rămâi.
  </p>
</section>

<!-- Confidentialitate -->
<section class="sectiune">
  <p class="eticheta">Confidențialitate</p>
  <h2>Ce se întâmplă cu ce spui aici</h2>
  <p style="margin-top: var(--s3)">
    Rămâne aici. Confidențialitatea e o obligație profesională, nu o amabilitate.
    Nu discut cu nimeni ce vorbim, nu confirm nimănui că ești în terapie.
  </p>
  <p>
    Îmi notez, între ședințe, câteva lucruri pentru mine — ca să nu te pun să
    reiei de fiecare dată de la capăt. Notele sunt păstrate în siguranță și intră
    sub aceeași confidențialitate.
  </p>
  <p>
    Există trei excepții, prevăzute de lege, și ți le spun din prima ședință, nu
    ascunse în subsol:
  </p>
  <ul>
    <li>un risc iminent pentru viața ta sau a altcuiva;</li>
    <li>suspiciunea de abuz asupra unui copil sau a unei persoane vulnerabile;</li>
    <li>o solicitare a unei instanțe de judecată.</li>
  </ul>
  <p style="margin-top: var(--s3)">
    În rest, ce e în cameră rămâne în cameră. Cum sunt tratate datele tale găsești
    în <a href="<?= e(url('politica-de-confidentialitate')) ?>">politica de confidențialitate</a>.
  </p>
</section>

<!-- Nota de criza -->
<section class="sectiune">
  <div class="nota-criza pe-tot-ecranul">
    <p class="fara-margine-jos">
      <strong>Dacă ești în criză acum,</strong> acest site nu e canalul potrivit —
      răspund la mesaje în câteva zile, nu în timp real. Pentru urgențe sună la
      <strong>112</strong>, sau la Telefonul de urgență pentru prevenirea
      suicidului, <strong>0800 801 200</strong> (gratuit, non-stop).
    </p>
  </div>
</section>

<?= view('public/_cta') ?>
