/* Pop-up pentru newsletter.

   Reguli, ca să nu deranjeze pe cineva venit să citească despre anxietate:
   - apare abia după ce vizitatorul a ales în bannerul de cookies (nu se suprapun);
   - apoi după 12 secunde sau după ce a derulat 40% din pagină;
   - închis (X, Esc, clic în afară, „Nu acum”) → nu mai apare 14 zile;
   - după abonare → nu mai apare deloc;
   - nu apare dacă secțiunea #newsletter din pagină e deja pe ecran.
   Formularul trimite la Web3Forms, la fel ca cel din pagina principală. */
(function () {
  var CHEIE = 'newsletter-popup-v1';
  var ZILE_PAUZA = 14;
  var ACCESS_KEY = 'beedf521-7a91-4329-9b2f-816696abb58d';

  function citeste(k) { try { return JSON.parse(localStorage.getItem(k) || 'null'); } catch (e) { return null; } }
  function scrie(v) { try { localStorage.setItem(CHEIE, JSON.stringify(v)); } catch (e) {} }

  var stare = citeste(CHEIE);
  if (stare && (stare.abonat || (Date.now() - new Date(stare.inchis).getTime()) < ZILE_PAUZA * 864e5)) return;

  var css =
    '.np{position:fixed;inset:0;z-index:95;display:grid;place-items:center;padding:1rem;' +
    'background:rgba(40,26,22,.45);backdrop-filter:blur(3px);opacity:0;transition:opacity .35s}' +
    '.np.np--in{opacity:1}.np[hidden]{display:none}' +
    '.np__box{position:relative;width:min(30rem,100%);max-height:calc(100vh - 2rem);overflow:auto;' +
    'background:var(--surface,#FFFDFC);color:var(--ink,#3C2B26);border:1px solid var(--line,#E8D8CD);' +
    'border-radius:24px;box-shadow:0 30px 80px rgba(60,36,28,.3);padding:2.2rem 1.6rem 1.6rem;' +
    'transform:translateY(14px);transition:transform .45s cubic-bezier(.16,1,.3,1)}' +
    '.np--in .np__box{transform:none}' +
    '@media (prefers-reduced-motion:reduce){.np,.np__box{transition:none}}' +
    '.np__x{position:absolute;top:.7rem;right:.7rem;width:2.4rem;height:2.4rem;border:0;border-radius:50%;' +
    'background:transparent;color:var(--ink-soft,#6F5B53);font-size:1.5rem;line-height:1;cursor:pointer}' +
    '.np__x:hover{background:var(--surface-2,#F2E3D8)}' +
    '.np__eyebrow{margin:0;font-size:.75rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--sage,#747860)}' +
    '.np h2{font-family:var(--serif,Georgia,serif);font-weight:600;font-size:1.75rem;line-height:1.15;margin:.5rem 0 .6rem}' +
    '.np__text{margin:0 0 1.2rem;color:var(--ink-soft,#6F5B53);font-size:.98rem;line-height:1.6}' +
    '.np input[type=email]{width:100%;font:inherit;color:var(--ink,#3C2B26);background:var(--bg,#F8F1EA);' +
    'border:1px solid var(--line-2,#D9C3B6);border-radius:12px;padding:.85rem 1rem}' +
    '.np input[type=email]:focus{outline:2px solid var(--sage,#747860);outline-offset:1px}' +
    '.np__consent{display:flex;gap:.6rem;align-items:flex-start;margin:.8rem 0 1rem;font-size:.8rem;line-height:1.5;color:var(--ink-soft,#6F5B53)}' +
    '.np__consent input{margin-top:.2rem;accent-color:var(--sage,#747860)}.np a{color:var(--sage,#747860)}' +
    '.np__btn{width:100%;font:inherit;font-weight:600;cursor:pointer;border:0;border-radius:999px;padding:.85rem 1.2rem;' +
    'background:var(--sage,#747860);color:#fff}.np__btn[disabled]{opacity:.6;cursor:default}' +
    '@media (prefers-color-scheme:dark){.np__btn{color:#10201A}}' +
    '.np__nu{display:block;margin:.8rem auto 0;border:0;background:none;font:inherit;font-size:.85rem;' +
    'color:var(--ink-faint,#907B72);cursor:pointer;text-decoration:underline}' +
    '.np__msg{margin:.8rem 0 0;font-size:.9rem;min-height:1em}.np__msg.err{color:#A04A3A}' +
    '.np .hp{position:absolute;left:-9999px;width:1px;height:1px}';

  var modal, ultimulFocus, pornit = false;

  function construieste() {
    var stil = document.createElement('style');
    stil.textContent = css;
    document.head.appendChild(stil);

    modal = document.createElement('div');
    modal.className = 'np';
    modal.hidden = true;
    modal.innerHTML =
      '<div class="np__box" role="dialog" aria-modal="true" aria-labelledby="np-titlu">' +
      '<button type="button" class="np__x" aria-label="Închide">&times;</button>' +
      '<p class="np__eyebrow">Newsletter</p>' +
      '<h2 id="np-titlu">Psihologie explicată fără grabă.</h2>' +
      '<p class="np__text">Primești articole noi și idei practice despre anxietate, epuizare, relații și parenting. ' +
      'Fără mesaje dese și fără promisiuni simpliste.</p>' +
      '<form novalidate>' +
      '<input type="hidden" name="access_key" value="' + ACCESS_KEY + '">' +
      '<input type="hidden" name="subject" value="Abonare nouă la newsletter — cristinaadam.ro">' +
      '<input type="hidden" name="from_name" value="Newsletter Adam Cristina">' +
      '<input type="hidden" name="tip" value="Abonare newsletter (pop-up)">' +
      '<label class="sr-only" for="np-email" style="position:absolute;left:-9999px">Adresa de email</label>' +
      '<input id="np-email" type="email" name="email" required autocomplete="email" placeholder="adresa@exemplu.ro">' +
      '<label class="np__consent"><input type="checkbox" name="consimtamant" value="Da" required>' +
      '<span>Sunt de acord să primesc newsletterul și am citit <a href="/confidentialitate">politica de confidențialitate</a>. ' +
      'Mă pot retrage oricând.</span></label>' +
      '<input type="checkbox" name="botcheck" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">' +
      '<button type="submit" class="np__btn">Mă abonez</button>' +
      '<p class="np__msg" role="status" aria-live="polite"></p>' +
      '</form>' +
      '<button type="button" class="np__nu">Nu acum</button>' +
      '</div>';
    document.body.appendChild(modal);

    modal.addEventListener('click', function (ev) {
      if (ev.target === modal || ev.target.closest('.np__x') || ev.target.closest('.np__nu')) inchide(true);
    });
    document.addEventListener('keydown', function (ev) {
      if (modal.hidden) return;
      if (ev.key === 'Escape') inchide(true);
      if (ev.key === 'Tab') {
        var f = modal.querySelectorAll('button, input:not([type=hidden]):not(.hp), a[href]');
        var prim = f[0], ultim = f[f.length - 1];
        if (ev.shiftKey && document.activeElement === prim) { ev.preventDefault(); ultim.focus(); }
        else if (!ev.shiftKey && document.activeElement === ultim) { ev.preventDefault(); prim.focus(); }
      }
    });

    var form = modal.querySelector('form');
    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      var msg = modal.querySelector('.np__msg');
      var btn = modal.querySelector('.np__btn');
      if (form.botcheck.checked) return;
      if (!form.email.value || !form.email.checkValidity()) {
        msg.textContent = 'Scrie o adresă de email validă.'; msg.className = 'np__msg err'; form.email.focus(); return;
      }
      if (!form.consimtamant.checked) {
        msg.textContent = 'Bifează acordul ca să te poți abona.'; msg.className = 'np__msg err'; return;
      }
      btn.disabled = true; btn.textContent = 'Se înscrie...'; msg.textContent = ''; msg.className = 'np__msg';
      var data = {}; new FormData(form).forEach(function (v, k) { data[k] = v; });
      fetch('https://api.web3forms.com/submit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify(data)
      }).then(function (r) { return r.json(); }).then(function (j) {
        if (!j.success) throw new Error(j.message || 'eroare');
        scrie({ abonat: true, la: new Date().toISOString() });
        if (window.dataLayer) window.dataLayer.push({ event: 'newsletter_abonare', sursa: 'popup' });
        form.innerHTML = '<p class="np__text" style="margin:0">Te-ai înscris. Vei primi următorul articol pe email. Mulțumesc!</p>';
        modal.querySelector('.np__nu').textContent = 'Închide';
      }).catch(function () {
        msg.textContent = 'Înscrierea nu a reușit. Încearcă din nou sau scrie la terapiecunico@gmail.com.';
        msg.className = 'np__msg err'; btn.disabled = false; btn.textContent = 'Mă abonez';
      });
    });
  }

  function deschide() {
    if (pornit) return;
    var sectiune = document.getElementById('newsletter');
    if (sectiune) {
      var r = sectiune.getBoundingClientRect();
      if (r.top < window.innerHeight && r.bottom > 0) return; // e deja pe ecran
    }
    pornit = true;
    construieste();
    ultimulFocus = document.activeElement;
    modal.hidden = false;
    requestAnimationFrame(function () { modal.classList.add('np--in'); });
    modal.querySelector('#np-email').focus({ preventScroll: true });
    window.removeEventListener('scroll', laDerulare);
  }

  function inchide(salveaza) {
    var abonat = (citeste(CHEIE) || {}).abonat;
    if (salveaza && !abonat) scrie({ inchis: new Date().toISOString() });
    modal.classList.remove('np--in');
    setTimeout(function () { modal.hidden = true; }, 300);
    if (ultimulFocus && ultimulFocus.focus) ultimulFocus.focus({ preventScroll: true });
  }

  function laDerulare() {
    var h = document.documentElement;
    if ((h.scrollTop + window.innerHeight) / h.scrollHeight > 0.4) deschide();
  }

  function programeaza() {
    setTimeout(deschide, 12000);
    window.addEventListener('scroll', laDerulare, { passive: true });
  }

  function start() {
    if (citeste('consimtamant-v1') !== null) programeaza();
    else document.addEventListener('consimtamant', programeaza, { once: true });
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
  else start();
})();
