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
