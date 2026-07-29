/* ==========================================================================
   JavaScript pentru partea publica.

   Fara framework, fara dependinte. Tot ce e aici functioneaza si daca
   scriptul nu se incarca: meniul are legaturi reale, FAQ-ul foloseste
   <details> nativ, formularul se trimite normal.

   Regula de miscare: nimic nu se misca daca vizitatorul nu a provocat
   miscarea. Nu exista niciun observator de scroll in acest fisier.
   ========================================================================== */

(function () {
  'use strict';

  /* ------------------------------------------------------------------------
     Apariție blândă la scroll

     Singura mișcare provocată de scroll pe tot site-ul, și e deliberat una
     calmă: secțiunile intră cu un fade și o urcare de câțiva pixeli, o singură
     dată. Nimic nu sare, nimic nu se repetă.

     Dacă vizitatorul a cerut mai puțină mișcare, sau browserul nu are
     IntersectionObserver, totul se afișează normal, fără animație.
     ------------------------------------------------------------------------ */

  var vreaMiscare = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var deRevelat = document.querySelectorAll('.reveal');

  if (vreaMiscare && 'IntersectionObserver' in window && deRevelat.length) {
    var observator = new IntersectionObserver(function (intrari) {
      intrari.forEach(function (intrare) {
        if (intrare.isIntersecting) {
          intrare.target.classList.add('vizibil');
          observator.unobserve(intrare.target); // o singură dată
        }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    deRevelat.forEach(function (el) { observator.observe(el); });
  } else {
    // Fără observator: arată tot imediat.
    deRevelat.forEach(function (el) { el.classList.add('vizibil'); });
  }

  /* ------------------------------------------------------------------------
     Feedback la scroll: bară de progres, CTA fix pe mobil, buton „sus"

     Un singur handler de scroll, temperat cu requestAnimationFrame, pentru toate
     trei — ca să nu punem trei ascultători care se calcă pe scroll.
     ------------------------------------------------------------------------ */

  var bara = document.querySelector('.progres-scroll__bara');
  var ctaMobil = document.querySelector('.cta-mobil');
  var butonSus = document.querySelector('.sus');
  var body = document.body;
  var ctaPrag = 0;

  // Pragul de la care apare CTA-ul mobil: după ce trece de hero (sau ~500px).
  var hero = document.querySelector('.hero');
  ctaPrag = hero ? Math.max(320, hero.offsetTop + hero.offsetHeight - 120) : 500;

  var tichetScroll = false;
  function laScroll() {
    var y = window.scrollY || document.documentElement.scrollTop;
    var inaltime = document.documentElement.scrollHeight - window.innerHeight;

    if (bara) {
      var progres = inaltime > 0 ? y / inaltime : 0;
      bara.style.transform = 'scaleX(' + Math.min(1, Math.max(0, progres)) + ')';
    }
    if (butonSus) {
      butonSus.classList.toggle('vizibil', y > 500);
    }
    if (ctaMobil) {
      var arata = y > ctaPrag;
      ctaMobil.classList.toggle('vizibil', arata);
      body.classList.toggle('cta-mobil-activ', arata);
    }
    tichetScroll = false;
  }

  if (bara || ctaMobil || butonSus) {
    window.addEventListener('scroll', function () {
      if (!tichetScroll) { tichetScroll = true; window.requestAnimationFrame(laScroll); }
    }, { passive: true });
    laScroll(); // stare initiala
  }

  if (butonSus) {
    butonSus.addEventListener('click', function () {
      var lin = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      window.scrollTo({ top: 0, behavior: lin ? 'smooth' : 'auto' });
    });
  }

  /* ------------------------------------------------------------------------
     Meniul mobil
     ------------------------------------------------------------------------ */

  var buton = document.querySelector('.buton-meniu');
  var meniu = document.getElementById('meniu-mobil');

  if (buton && meniu) {
    buton.addEventListener('click', function () {
      var deschis = meniu.getAttribute('data-deschis') === 'true';
      meniu.setAttribute('data-deschis', String(!deschis));
      buton.setAttribute('aria-expanded', String(!deschis));
    });

    // Escape inchide meniul si duce focusul inapoi pe buton.
    document.addEventListener('keydown', function (ev) {
      if (ev.key === 'Escape' && meniu.getAttribute('data-deschis') === 'true') {
        meniu.setAttribute('data-deschis', 'false');
        buton.setAttribute('aria-expanded', 'false');
        buton.focus();
      }
    });
  }

  /* ------------------------------------------------------------------------
     Consimtamant

     Alegerea se tine in localStorage, nu intr-un cookie: un cookie ar trebui
     el insusi declarat, si ar pleca la fiecare cerere degeaba.
     ------------------------------------------------------------------------ */

  var CHEIE = 'consimtamant-v1';
  var banner = document.getElementById('banner-cookies');

  function citesteAlegerea() {
    try {
      return JSON.parse(localStorage.getItem(CHEIE) || 'null');
    } catch (err) {
      return null;
    }
  }

  function scrieAlegerea(analiza) {
    try {
      localStorage.setItem(CHEIE, JSON.stringify({
        analiza: analiza,
        la: new Date().toISOString()
      }));
    } catch (err) {
      /* Mod privat, sau stocare plina. Nu insistam. */
    }
  }

  // Incarca scriptul de analiza. Apelat DOAR dupa accept explicit.
  function pornesteAnaliza() {
    var sursa = document.querySelector('meta[name="analiza-script"]');
    var domeniu = document.querySelector('meta[name="analiza-domeniu"]');
    if (!sursa || !domeniu || document.getElementById('script-analiza')) return;

    var s = document.createElement('script');
    s.id = 'script-analiza';
    s.defer = true;
    s.src = sursa.content;
    s.setAttribute('data-domain', domeniu.content);
    document.head.appendChild(s);
  }

  if (banner) {
    var alegere = citesteAlegerea();

    if (alegere === null) {
      banner.hidden = false;
    } else if (alegere.analiza) {
      pornesteAnaliza();
    }

    banner.addEventListener('click', function (ev) {
      var actiune = ev.target.getAttribute('data-cookies');
      if (!actiune) return;

      var bifa = document.getElementById('consimtamant-analiza');
      var acceptaAnaliza = actiune === 'salveaza' && bifa && bifa.checked;

      scrieAlegerea(!!acceptaAnaliza);
      banner.hidden = true;
      if (acceptaAnaliza) pornesteAnaliza();
    });
  }

  /* ------------------------------------------------------------------------
     Formularul de contact

     Marcheaza momentul in care pagina a fost incarcata. Serverul compara cu
     momentul trimiterii: un formular completat in mai putin de trei secunde
     e aproape sigur un robot. Impreuna cu campul-capcana, asta inlocuieste
     complet reCAPTCHA — si evita un transfer de date catre Google de pe un
     site de sanatate.
     ------------------------------------------------------------------------ */

  var campTimp = document.getElementById('incarcat_la');
  if (campTimp) {
    campTimp.value = String(Math.floor(Date.now() / 1000));
  }

  /* ------------------------------------------------------------------------
     Validare la trimitere

     Mesajele apar prin opacitate, fara deplasare: deplasarea ar muta campurile
     sub degetul cuiva care tocmai a gresit. (DESIGN.md §5, nr. 3)
     ------------------------------------------------------------------------ */

  var formular = document.querySelector('form[data-valideaza]');
  if (formular) {
    formular.addEventListener('submit', function (ev) {
      var primulInvalid = null;

      Array.prototype.forEach.call(formular.querySelectorAll('[required]'), function (camp) {
        var eroare = document.getElementById('eroare-' + camp.name);
        var valid = camp.checkValidity();

        if (eroare) {
          eroare.textContent = valid ? '' : (camp.getAttribute('data-eroare') || 'Câmp obligatoriu.');
          eroare.setAttribute('data-vizibil', String(!valid));
        }
        camp.setAttribute('aria-invalid', String(!valid));
        if (!valid && !primulInvalid) primulInvalid = camp;
      });

      if (primulInvalid) {
        ev.preventDefault();
        primulInvalid.focus();
      }
    });
  }
})();
