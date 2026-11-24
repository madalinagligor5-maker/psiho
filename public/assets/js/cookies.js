/* Banner de consimțământ pentru cookies de analiză (Google Tag Manager).

   Google Consent Mode v2: în <head> totul pornește „denied”. Abia după ce
   vizitatorul apasă „Accept”, trimitem gtag('consent','update', …granted).
   Publicitatea rămâne mereu refuzată: site-ul nu folosește reclame.

   Alegerea se ține în localStorage (aceeași cheie ca versiunea PHP), nu într-un
   cookie. Linkurile cu atributul data-cookies-setari redeschid bannerul. */
(function () {
  var CHEIE = 'consimtamant-v1';

  function citeste() {
    try { return JSON.parse(localStorage.getItem(CHEIE) || 'null'); } catch (e) { return null; }
  }
  function scrie(analiza) {
    try { localStorage.setItem(CHEIE, JSON.stringify({ analiza: analiza, la: new Date().toISOString() })); } catch (e) {}
  }
  function aplica(analiza) {
    if (typeof window.gtag !== 'function') return;
    window.gtag('consent', 'update', { analytics_storage: analiza ? 'granted' : 'denied' });
    window.dataLayer.push({ event: analiza ? 'consimtamant_acceptat' : 'consimtamant_refuzat' });
  }

  var css =
    '.cb{position:fixed;z-index:90;left:1rem;right:1rem;bottom:1rem;max-width:34rem;margin-inline:auto;' +
    'background:var(--surface,#FFFDFC);color:var(--ink,#3C2B26);border:1px solid var(--line,#E8D8CD);' +
    'border-radius:16px;box-shadow:0 18px 48px rgba(104,66,50,.18);padding:1.1rem 1.2rem;font-size:.92rem;line-height:1.55}' +
    '.cb[hidden]{display:none}' +
    '.cb p{margin:0 0 .9rem}.cb a{color:var(--sage,#747860)}' +
    '.cb__act{display:flex;flex-wrap:wrap;gap:.6rem}' +
    '.cb__act button{font:inherit;font-weight:600;font-size:.9rem;cursor:pointer;border-radius:999px;padding:.6rem 1.2rem;' +
    'border:1px solid var(--line-2,#D9C3B6);background:transparent;color:var(--ink,#3C2B26)}' +
    '.cb__act .cb__da{background:var(--sage,#747860);border-color:var(--sage,#747860);color:#fff}' +
    '@media (prefers-color-scheme:dark){.cb__act .cb__da{color:#10201A}}';

  var stil = document.createElement('style');
  stil.textContent = css;
  document.head.appendChild(stil);

  var banner = document.createElement('div');
  banner.className = 'cb';
  banner.setAttribute('role', 'dialog');
  banner.setAttribute('aria-label', 'Preferințe cookies');
  banner.hidden = true;
  banner.innerHTML =
    '<p>Folosesc cookies de analiză (Google) doar dacă ești de acord, ca să văd anonim ce pagini sunt citite. ' +
    'Nu folosesc reclame. Detalii în <a href="/cookies">politica de cookies</a>.</p>' +
    '<div class="cb__act">' +
    '<button type="button" class="cb__da" data-alegere="da">Accept</button>' +
    '<button type="button" data-alegere="nu">Refuz</button>' +
    '</div>';

  function porneste() {
    document.body.appendChild(banner);
    if (citeste() === null) banner.hidden = false;

    banner.addEventListener('click', function (ev) {
      var a = ev.target.getAttribute && ev.target.getAttribute('data-alegere');
      if (!a) return;
      var analiza = a === 'da';
      scrie(analiza);
      aplica(analiza);
      banner.hidden = true;
      document.dispatchEvent(new CustomEvent('consimtamant', { detail: { analiza: analiza } }));
    });

    document.addEventListener('click', function (ev) {
      var t = ev.target.closest && ev.target.closest('[data-cookies-setari]');
      if (!t) return;
      ev.preventDefault();
      banner.hidden = false;
      var b = banner.querySelector('button');
      if (b) b.focus();
    });
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', porneste);
  else porneste();
})();
