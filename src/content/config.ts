import { defineCollection, z } from 'astro:content';

const articole = defineCollection({
  type: 'content',
  schema: z.object({
    titlu: z.string(),
    rezumat: z.string(),
    categorie: z.string().optional(),
    data: z.string().optional(),
  }),
});

const pagini = defineCollection({
  type: 'content',
  schema: z.object({
    titlu: z.string(),
  }),
});

const echipa = defineCollection({
  type: 'content',
  schema: z.object({
    nume: z.string(),
    titlu: z.string(),
    cpr: z.string(),
    judet: z.string().optional(),
    regim: z.string().optional(),
    ordine: z.number().default(1),
  }),
});

export const collections = { articole, pagini, echipa };
