<?php
/**
 * Contact. Formular de maximum 4 campuri (§5).
 * Variabile: $trimis, $erori, $vechi
 */
$vechi   = $vechi ?? [];
$erori   = $erori ?? [];
$trimis  = $trimis ?? false;
$email   = (string) config('site', 'email');
$telefon = (string) config('site', 'telefon');
$whatsapp = (string) config('site', 'whatsapp');
?>

<?= view('public/_hero', [
  'h_eticheta' => 'Contact',
  'h_titlu'    => 'Hai să vorbim o dată',
  'h_sub'      => 'Nu trebuie să știi ce să spui. Un rând ajunge. Îți răspundem în maximum 48 '
    . 'de ore lucrătoare și stabilim o ședință de cunoaștere, gratuită.',
]) ?>

<section class="sectiune" style="padding-block-start: 0">
  <?php if ($trimis): ?>
    <div class="mesaj-succes pe-tot-ecranul">
      <p class="fara-margine-jos">
        <strong>Am primit mesajul.</strong> Îți răspundem în maximum 48 de ore
        lucrătoare. Dacă între timp e ceva urgent, ne găsești mai jos pe WhatsApp
        sau telefon.
      </p>
    </div>
  <?php else: ?>

    <form method="post" action="<?= e(url('contact')) ?>" data-valideaza novalidate>
      <?= Auth::campCsrf() ?>
      <input type="hidden" name="incarcat_la" id="incarcat_la" value="">

      <!-- Camp-capcana. Ascuns pentru oameni; robotii il completeaza. -->
      <div class="capcana" aria-hidden="true">
        <label>Nu completa acest câmp
          <input type="text" name="website" tabindex="-1" autocomplete="off">
        </label>
      </div>

      <div class="camp">
        <label for="nume">Cum să-ți spunem?</label>
        <input type="text" id="nume" name="nume" required
               data-eroare="Spune-ne cum să-ți spunem."
               value="<?= e($vechi['nume'] ?? '') ?>"
               autocomplete="given-name">
        <span class="eroare-camp" id="eroare-nume" data-vizibil="<?= isset($erori['nume']) ? 'true' : 'false' ?>"><?= e($erori['nume'] ?? '') ?></span>
      </div>

      <div class="camp">
        <label for="contact">Email sau telefon</label>
        <input type="text" id="contact" name="contact" required
               data-eroare="Am nevoie de un email sau un telefon ca să-ți putem răspunde."
               value="<?= e($vechi['contact'] ?? '') ?>"
               autocomplete="email">
        <span class="ajutor">Cum preferi să te contactăm. Nu apare nicăieri public.</span>
        <span class="eroare-camp" id="eroare-contact" data-vizibil="<?= isset($erori['contact']) ? 'true' : 'false' ?>"><?= e($erori['contact'] ?? '') ?></span>
      </div>

      <div class="camp">
        <label for="preferat">Preferi să discuți cu cineva anume? <span class="ajutor" style="font-weight:400">(opțional)</span></label>
        <select id="preferat" name="preferat">
          <option value="0">Nu am o preferință</option>
          <?php foreach (($psihologi ?? []) as $ps): ?>
            <option value="<?= (int) $ps['id'] ?>" <?= (int) ($vechi['preferat'] ?? 0) === (int) $ps['id'] ? 'selected' : '' ?>><?= e($ps['nume']) ?></option>
          <?php endforeach ?>
        </select>
        <span class="ajutor">Dacă nu ești sigur, lasă „Nu am o preferință” — te îndrumăm noi.</span>
      </div>

      <div class="camp">
        <label for="mesaj">Vrei să adaugi ceva? <span class="ajutor" style="font-weight:400">(opțional)</span></label>
        <textarea id="mesaj" name="mesaj" rows="4"><?= e($vechi['mesaj'] ?? '') ?></textarea>
      </div>

      <button type="submit" class="buton buton--principal">Trimite</button>
      <p class="meta" style="margin-top: var(--s2)">
        Trimițând, ești de acord cu
        <a href="<?= e(url('politica-de-confidentialitate')) ?>">politica de confidențialitate</a>.
      </p>
    </form>

  <?php endif ?>
</section>

<!-- Alte cai de contact -->
<section class="sectiune">
  <p class="eticheta">Sau direct</p>
  <h2>Alte feluri de a ne găsi</h2>
  <div class="stiva" style="margin-top: var(--s3)">
    <?php if ($whatsapp !== '' && !str_contains($whatsapp, 'COMPLETEZ')): ?>
      <p class="fara-margine-jos"><strong>WhatsApp:</strong> <a href="https://wa.me/<?= e($whatsapp) ?>" target="_blank" rel="noopener">scrie-ne un mesaj</a></p>
    <?php endif ?>
    <?php if ($email !== '' && !str_contains($email, 'COMPLETEZ')): ?>
      <p class="fara-margine-jos"><strong>Email:</strong> <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></p>
    <?php endif ?>
    <?php if ($telefon !== '' && !str_contains($telefon, 'COMPLETEZ')): ?>
      <p class="fara-margine-jos"><strong>Telefon:</strong> <a href="tel:<?= e(preg_replace('/\s+/', '', $telefon)) ?>"><?= e($telefon) ?></a></p>
    <?php endif ?>
  </div>
</section>

<!-- Cabinet: adresa + program. Harta e imagine statica, nu Google Maps embed. -->
<section class="sectiune">
  <p class="eticheta">Cabinetul</p>
  <h2>Unde și când</h2>
  <div style="margin-top: var(--s3)">
    <p class="fara-margine-jos"><strong>Adresă:</strong> <?= e(setare('adresa')) ?></p>
    <p style="margin-top: var(--s2); white-space: pre-line"><strong>Program:</strong>
<?= e(setare('program')) ?></p>
    <p class="meta"><?= e(setare('timp_raspuns', 'Răspundem în maximum 48 de ore lucrătoare.')) ?></p>
  </div>

  <?php /* Harta statica: o imagine, nu un embed. Un embed Google Maps incarca
           scripturi Google si trimite date inainte de orice consimtamant. */ ?>
  <figure style="margin: var(--s4) 0 0">
    <img src="<?= e(asset('assets/images/harta.webp')) ?>"
         alt="Hartă cu locația cabinetului: <?= e(setare('adresa')) ?>"
         width="1200" height="600" loading="lazy"
         style="border-radius: var(--raza-mediu); border: 1px solid var(--greige)">
    <figcaption class="meta" style="margin-top: var(--s1)">
      <a href="https://www.openstreetmap.org/search?query=<?= rawurlencode(setare('adresa')) ?>" target="_blank" rel="noopener">Deschide harta →</a>
    </figcaption>
  </figure>
</section>

<!-- Nota de criza -->
<section class="sectiune">
  <div class="nota-criza pe-tot-ecranul">
    <p class="fara-margine-jos">
      <strong>Dacă ești în criză acum,</strong> acest formular nu e canalul
      potrivit — nu îl citim în timp real. Sună la <strong>112</strong>, sau la
      Telefonul de urgență pentru prevenirea suicidului,
      <strong>0800 801 200</strong> (gratuit, non-stop).
    </p>
  </div>
</section>
