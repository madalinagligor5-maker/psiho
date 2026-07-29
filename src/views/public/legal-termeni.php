<?php /** Termeni si conditii. */ ?>

<section class="sectiune">
  <p class="eticheta">Legal</p>
  <h1>Termeni și condiții</h1>
  <p class="meta">Ultima actualizare: [COMPLETEZ EU: data]</p>
</section>

<section class="sectiune" style="padding-block-start: 0">
  <div class="proza">
    <h2>Despre acest site</h2>
    <p>
      Acest site prezintă serviciile de psihologie și psihoterapie oferite de
      <strong><?= e(setare('cabinet_entitate', 'ADAM ȘI BABOTAN, Societate civilă profesională de psihologie')) ?></strong>
      și permite contactarea în vederea programării unei ședințe. Folosirea
      site-ului înseamnă acceptarea acestor termeni.
    </p>

    <h2>Ce nu este acest site</h2>
    <p>
      Informațiile de pe site au caracter general și educativ. <strong>Nu
      înlocuiesc o consultație</strong> și nu constituie diagnostic sau
      tratament. Citirea unui articol nu creează o relație terapeutică.
    </p>
    <p>
      Site-ul <strong>nu este un canal de intervenție în criză</strong>. Mesajele
      nu sunt citite în timp real. În caz de urgență, sună la <strong>112</strong>.
    </p>

    <h2>Programări și plăți</h2>
    <p>
      O programare devine fermă doar după confirmarea din partea mea. Prețurile
      afișate sunt în lei și pot fi actualizate; prețul valabil e cel comunicat
      la confirmarea ședinței. Condițiile de anulare sunt cele descrise pe pagina
      <a href="<?= e(url('cum-functioneaza')) ?>">Cum funcționează</a>.
    </p>

    <h2>Proprietate intelectuală</h2>
    <p>
      Textele și materialele de pe site îmi aparțin. Le poți citi și distribui cu
      menționarea sursei, dar nu le poți reproduce comercial fără acord.
    </p>

    <h2>Resurse și produse digitale</h2>
    <p>
      Resursele prezentate pe pagina <a href="<?= e(url('resurse')) ?>">Resurse</a>
      se solicită prin email. Condițiile specifice de folosire se comunică odată
      cu materialul.
    </p>

    <h2>Modificări</h2>
    <p>
      Acești termeni pot fi actualizați. Versiunea în vigoare e cea publicată aici,
      cu data ultimei actualizări de sus.
    </p>

    <h2>Contact</h2>
    <p>
      Pentru orice întrebare legată de acești termeni, scrie-mi la
      <a href="mailto:<?= e((string) config('site', 'email')) ?>"><?= e((string) config('site', 'email')) ?></a>.
    </p>
  </div>
</section>
