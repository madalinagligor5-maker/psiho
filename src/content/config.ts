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

export const collections = { articole };
