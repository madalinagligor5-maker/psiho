<?php
/**
 * Politica de confidentialitate.
 *
 * Nu e boilerplate: clienta prelucreaza date de sanatate, care sunt categorie
 * speciala in sensul Art. 9 GDPR. Politica trebuie sa spuna asta explicit.
 * Locurile marcate [COMPLETEZ EU] cer date reale (nume operator, CUI, adresa) —
 * nu le inventez.
 */
$retentie = (int) config('retentie_mesaje_zile');
?>

<section class="sectiune">
  <p class="eticheta">Legal</p>
  <h1>Politica de confidențialitate</h1>
  <p class="meta">Ultima actualizare: [COMPLETEZ EU: data]</p>
</section>

<section class="sectiune" style="padding-block-start: 0">
  <div class="proza">
    <p class="introducere">
      Această pagină explică ce date despre tine sunt prelucrate prin acest site
      și cum. E scrisă ca să fie citită, nu ca să bifeze o cerință.
    </p>

    <h2>Cine e responsabil</h2>
    <p>
      Operatorul datelor este <strong><?= e(setare('cabinet_entitate', 'ADAM ȘI BABOTAN, Societate civilă profesională de psihologie')) ?></strong>
      (<?= e(setare('cabinet_certificat', '')) ?>), cu sediul în
      <?= e(setare('adresa', '[COMPLETEZ EU: adresă]')) ?>. Ne poți contacta la
      <a href="mailto:<?= e((string) config('site', 'email')) ?>"><?= e((string) config('site', 'email')) ?></a>.
    </p>

    <h2>Date de sănătate — categorie specială</h2>
    <p>
      Dacă îmi devii client, prelucrez date despre starea ta psihică și fizică.
      Acestea sunt <strong>date de categorie specială</strong> în sensul
      articolului 9 din Regulamentul General privind Protecția Datelor (GDPR).
      Temeiul prelucrării lor este articolul 9(2)(h) — furnizarea de servicii de
      îngrijire a sănătății — și consimțământul tău explicit, exprimat la
      începutul colaborării.
    </p>
    <p>
      Notele de ședință sunt păstrate în siguranță, separat de acest site, și
      intră sub secretul profesional. Nu sunt stocate în baza de date a
      site-ului.
    </p>

    <h2>Ce colectează site-ul</h2>
    <p>Site-ul în sine colectează mult mai puțin:</p>
    <ul>
      <li>
        <strong>Formularul de contact:</strong> numele pe care îl dai, emailul
        sau telefonul, situația aleasă și mesajul opțional. Acestea sunt
        <strong>criptate</strong> în baza de date și îmi folosesc doar ca să îți
        pot răspunde.
      </li>
      <li>
        <strong>Statistici de vizitare</strong> — doar dacă accepți din bannerul
        de cookies. Sunt anonime, fără cookies și fără profilare. Vezi
        <a href="<?= e(url('politica-de-cookies')) ?>">politica de cookies</a>.
      </li>
      <li>
        <strong>Jurnale tehnice de server</strong>, păstrate de firma de
        găzduire pentru securitate, conform politicilor ei.
      </li>
    </ul>

    <h2>Cât timp păstrez datele</h2>
    <p>
      Mesajele din formularul de contact se șterg automat după
      <strong><?= e((string) $retentie) ?> de zile</strong> de la primire. Dacă
      devenim colaboratori, datele relevante trec în dosarul de client, cu
      propriile reguli de păstrare, prevăzute de standardele profesiei.
    </p>

    <h2>Cui le transmit</h2>
    <p>
      Nu vând și nu transmit datele tale nimănui în scop comercial. Le pot accesa
      doar furnizorii tehnici strict necesari (găzduire, serviciu de email), sub
      obligație de confidențialitate. Statisticile de vizitare, dacă le accepți,
      sunt prelucrate de [COMPLETEZ EU: Plausible / Matomo], care nu construiește
      profiluri și nu transferă datele în afara UE.
    </p>

    <h2>Drepturile tale</h2>
    <p>Conform GDPR, ai dreptul:</p>
    <ul>
      <li>să ceri o copie a datelor pe care le am despre tine;</li>
      <li>să ceri corectarea lor;</li>
      <li>să ceri ștergerea lor, în limitele obligațiilor mele legale de păstrare a dosarului de client;</li>
      <li>să retragi consimțământul oricând;</li>
      <li>să depui o plângere la Autoritatea Națională de Supraveghere a Prelucrării Datelor cu Caracter Personal (ANSPDCP), <a href="https://www.dataprotection.ro" target="_blank" rel="noopener">dataprotection.ro</a>.</li>
    </ul>
    <p>
      Pentru oricare dintre ele, scrie-mi la
      <a href="mailto:<?= e((string) config('site', 'email')) ?>"><?= e((string) config('site', 'email')) ?></a>.
    </p>

    <h2>Securitate</h2>
    <p>
      Site-ul funcționează numai peste HTTPS. Mesajele de contact sunt criptate
      la rest. Accesul la panoul de administrare e protejat prin parolă și
      limitare a încercărilor de autentificare.
    </p>
  </div>
</section>
