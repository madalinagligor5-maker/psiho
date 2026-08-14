import { defineConfig } from 'astro/config';

// Pagina principala animata ramane statica in public/ (o servim ca atare).
// Astro genereaza paginile de blog din src/content/articole/*.md.
export default defineConfig({
  site: 'https://psiho-sepia.vercel.app',
  publicDir: 'public',
  outDir: 'dist',
});
