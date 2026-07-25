/* ==========================================================================
   Sursa JavaScript-ului de admin. Se construieste cu esbuild intr-un singur
   fisier (public_html/assets/js/admin.js) care se comite. Serverul nu are pas
   de build — vezi package.json.

   Contine tot ce tine de modul „Operate": editorul TipTap, incarcarea de
   imagini, generarea slugului, confirmarile de stergere. Aici miscarea e
   feedback si trebuie sa fie buna.
   ========================================================================== */

import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';
import { Markdown } from 'tiptap-markdown';

/* --------------------------------------------------------------------------
   Slug din titlu, cu diacriticele romanesti transliterate.
   Aceeasi harta ca in PHP (slugify), ca sa nu difere client de server.
   -------------------------------------------------------------------------- */

const HARTA_SLUG = {
  ă: 'a', â: 'a', î: 'i', ș: 's', ț: 't',
  Ă: 'a', Â: 'a', Î: 'i', Ș: 's', Ț: 't',
  ş: 's', ţ: 't', Ş: 's', Ţ: 't',
};

function slugify(text) {
  return text
    .replace(/[ăâîșțĂÂÎȘȚşţŞŢ]/g, (c) => HARTA_SLUG[c] || c)
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

function porneisteSlug() {
  const titlu = document.querySelector('[data-genereaza-slug]');
  const slug = document.getElementById('slug');
  if (!titlu || !slug) return;

  // Genereaza slugul din titlu doar cat timp campul de slug nu a fost atins de
  // mana. Odata editat manual, nu-l mai suprascriem.
  let atinsManual = slug.value.trim() !== '';
  slug.addEventListener('input', () => { atinsManual = true; });
  titlu.addEventListener('input', () => {
    if (!atinsManual) slug.value = slugify(titlu.value);
  });
}

/* --------------------------------------------------------------------------
   Confirmare inainte de o actiune ireversibila.
   -------------------------------------------------------------------------- */

function porneisteConfirmari() {
  document.addEventListener('click', (ev) => {
    const el = ev.target.closest('[data-confirma]');
    if (el && !window.confirm(el.getAttribute('data-confirma'))) {
      ev.preventDefault();
    }
  });
}

/* --------------------------------------------------------------------------
   Incarcarea unei imagini catre server, cu bara de progres.
   Returneaza Promise cu URL-ul imaginii procesate (redimensionata, WebP).
   -------------------------------------------------------------------------- */

function incarcaImagine(fisier, urlIncarca, csrf, laProgres) {
  return new Promise((resolve, reject) => {
    const date = new FormData();
    date.append('imagine', fisier);
    date.append('csrf', csrf);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', urlIncarca);
    xhr.upload.addEventListener('progress', (e) => {
      if (e.lengthComputable && laProgres) laProgres(e.loaded / e.total);
    });
    xhr.addEventListener('load', () => {
      try {
        const r = JSON.parse(xhr.responseText);
        if (xhr.status === 200 && r.ok) resolve(r);
        else reject(new Error(r.eroare || 'Încărcarea a eșuat.'));
      } catch (err) {
        reject(new Error('Răspuns neașteptat de la server.'));
      }
    });
    xhr.addEventListener('error', () => reject(new Error('Conexiune întreruptă.')));
    xhr.send(date);
  });
}

/* Zona de incarcare a imaginii de coperta (drag & drop + click). */
function porneisteIncarcareCoperta() {
  const zona = document.querySelector('[data-incarca]');
  if (!zona) return;

  const campUrl = document.getElementById('imagine');
  const previzualizare = document.getElementById('imagine-previzualizare');
  const editorEl = document.querySelector('[data-editor]');
  const urlIncarca = editorEl?.getAttribute('data-incarca-url');
  const csrf = editorEl?.getAttribute('data-csrf');
  const progres = zona.querySelector('.incarca__progres');
  const bara = zona.querySelector('.incarca__bara');
  const text = zona.querySelector('[data-incarca-text]');

  const input = document.createElement('input');
  input.type = 'file';
  input.accept = 'image/*';
  input.hidden = true;
  zona.appendChild(input);

  async function trateaza(fisier) {
    if (!fisier) return;
    progres.hidden = false;
    text.textContent = 'Se încarcă...';
    try {
      const r = await incarcaImagine(fisier, urlIncarca, csrf, (p) => {
        bara.style.width = Math.round(p * 100) + '%';
      });
      campUrl.value = r.nume;
      previzualizare.innerHTML =
        '<img src="' + r.url + '" alt="" style="max-width:16rem;border-radius:var(--raza-mediu)">';
      text.textContent = 'Imagine încărcată. Apasă ca să o schimbi.';
    } catch (err) {
      text.textContent = 'Eroare: ' + err.message + ' Încearcă din nou.';
    } finally {
      setTimeout(() => { progres.hidden = true; bara.style.width = '0'; }, 600);
    }
  }

  zona.addEventListener('click', () => input.click());
  zona.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.click(); }
  });
  input.addEventListener('change', () => trateaza(input.files[0]));

  ['dragover', 'dragenter'].forEach((ev) =>
    zona.addEventListener(ev, (e) => { e.preventDefault(); zona.setAttribute('data-peste', 'true'); }));
  ['dragleave', 'drop'].forEach((ev) =>
    zona.addEventListener(ev, (e) => { e.preventDefault(); zona.removeAttribute('data-peste'); }));
  zona.addEventListener('drop', (e) => trateaza(e.dataTransfer.files[0]));
}

/* --------------------------------------------------------------------------
   Editorul TipTap.

   Textarea de Markdown ramane sursa adevarului la trimiterea formularului:
   editorul o rescrie la fiecare modificare. Extensia Markdown se ocupa si de
   citirea Markdown-ului existent la incarcare, si de serializarea inapoi.

   Sanitizare la lipire: TipTap pastreaza doar nodurile din schema (titluri,
   liste, gras, cursiv, citat, imagine, legatura). Stilul din Word — clase,
   culori, font-uri inline — nu are unde sa intre, deci nu supravietuieste.
   -------------------------------------------------------------------------- */

function porneisteEditor() {
  const gazda = document.querySelector('[data-editor]');
  const textarea = document.getElementById('continut');
  if (!gazda || !textarea) return;

  const urlIncarca = gazda.getAttribute('data-incarca-url');
  const csrf = gazda.getAttribute('data-csrf');
  const stare = document.querySelector('[data-editor-stare]');

  // Construieste bara de unelte si zona editabila.
  const unealta = document.createElement('div');
  unealta.className = 'editor-unealta';
  const zona = document.createElement('div');
  zona.className = 'editor-zona';
  gazda.append(unealta, zona);

  // Textarea devine ascunsa: ramane in DOM ca sa se trimita cu formularul.
  textarea.style.display = 'none';

  const editor = new Editor({
    element: zona,
    extensions: [
      StarterKit.configure({ heading: { levels: [2, 3] } }),
      Image.configure({ inline: false, HTMLAttributes: { loading: 'lazy' } }),
      Link.configure({ openOnClick: false, HTMLAttributes: { rel: 'noopener noreferrer' } }),
      Markdown.configure({ html: false, transformPastedText: true, transformCopiedText: true }),
    ],
    content: textarea.value,
    onUpdate: ({ editor }) => {
      // Scrie Markdown inapoi in textarea la fiecare modificare.
      textarea.value = editor.storage.markdown.getMarkdown();
      arataStare('lucreaza', 'Nesalvat');
    },
  });

  // La incarcarea Markdown-ului existent, extensia il parseaza singura.
  editor.commands.setContent(textarea.value);

  /* Bara de unelte. Fiecare buton actioneaza o comanda si isi arata starea. */
  const unelte = [
    { et: 'B', titlu: 'Îngroșat', cmd: () => editor.chain().focus().toggleBold().run(), activ: () => editor.isActive('bold') },
    { et: 'I', titlu: 'Cursiv', cmd: () => editor.chain().focus().toggleItalic().run(), activ: () => editor.isActive('italic') },
    { sep: true },
    { et: 'H2', titlu: 'Titlu de secțiune', cmd: () => editor.chain().focus().toggleHeading({ level: 2 }).run(), activ: () => editor.isActive('heading', { level: 2 }) },
    { et: 'H3', titlu: 'Subtitlu', cmd: () => editor.chain().focus().toggleHeading({ level: 3 }).run(), activ: () => editor.isActive('heading', { level: 3 }) },
    { sep: true },
    { et: '• Listă', titlu: 'Listă', cmd: () => editor.chain().focus().toggleBulletList().run(), activ: () => editor.isActive('bulletList') },
    { et: '1. Listă', titlu: 'Listă numerotată', cmd: () => editor.chain().focus().toggleOrderedList().run(), activ: () => editor.isActive('orderedList') },
    { et: '„ ”', titlu: 'Citat', cmd: () => editor.chain().focus().toggleBlockquote().run(), activ: () => editor.isActive('blockquote') },
    { sep: true },
    { et: '🔗', titlu: 'Legătură', cmd: adaugaLegatura, activ: () => editor.isActive('link') },
    { et: '🖼', titlu: 'Imagine', cmd: adaugaImagine, activ: () => false },
  ];

  const butoane = [];
  for (const u of unelte) {
    if (u.sep) {
      const s = document.createElement('span');
      s.className = 'separator';
      unealta.appendChild(s);
      continue;
    }
    const b = document.createElement('button');
    b.type = 'button';
    b.textContent = u.et;
    b.title = u.titlu;
    b.setAttribute('aria-label', u.titlu);
    b.addEventListener('click', () => { u.cmd(); actualizeazaButoane(); });
    unealta.appendChild(b);
    butoane.push({ b, activ: u.activ });
  }

  function actualizeazaButoane() {
    for (const { b, activ } of butoane) b.classList.toggle('activ', activ());
  }
  editor.on('selectionUpdate', actualizeazaButoane);
  editor.on('transaction', actualizeazaButoane);

  function adaugaLegatura() {
    const url = window.prompt('Adresa legăturii (https://...)');
    if (url === null) return;
    if (url === '') { editor.chain().focus().unsetLink().run(); return; }
    editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
  }

  async function adaugaImagine() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = async () => {
      const fisier = input.files[0];
      if (!fisier) return;
      arataStare('lucreaza', 'Se încarcă imaginea...');
      try {
        const r = await incarcaImagine(fisier, urlIncarca, csrf);
        editor.chain().focus().setImage({ src: r.url }).run();
        arataStare('gata', 'Imagine adăugată');
      } catch (err) {
        arataStare('', 'Eroare la imagine: ' + err.message);
      }
    };
    input.click();
  }

  function arataStare(tip, text) {
    if (!stare) return;
    stare.setAttribute('data-tip', tip);
    stare.textContent = text;
  }

  // Confirma vizibil ca formularul s-a trimis: „Se salvează…".
  const form = document.getElementById('form-articol');
  if (form) {
    form.addEventListener('submit', () => {
      textarea.value = editor.storage.markdown.getMarkdown();
      arataStare('lucreaza', 'Se salvează...');
    });
  }
}

/* --------------------------------------------------------------------------
   Pornire
   -------------------------------------------------------------------------- */

function porneiste() {
  porneisteSlug();
  porneisteConfirmari();
  porneisteIncarcareCoperta();
  porneisteEditor();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', porneiste);
} else {
  porneiste();
}
