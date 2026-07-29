<?php
declare(strict_types=1);

/**
 * Articole. Toate metodele publice returneaza doar articole publicate —
 * ciornele se citesc explicit, prin metodele cu prefix `admin`.
 */
final class Articol
{
    public const PE_PAGINA = 8;

    private const SELECT_PUBLIC = "
        SELECT a.*, c.nume AS categorie_nume, c.slug AS categorie_slug,
               p.nume AS autor_nume, p.slug AS autor_slug
        FROM articole a
        LEFT JOIN categorii c ON c.id = a.categorie_id
        LEFT JOIN psihologi p ON p.id = a.autor_id
    ";

    /** Ultimele articole publicate. Pentru prima pagina. */
    public static function recente(int $limita = 3): array
    {
        return Database::all(
            self::SELECT_PUBLIC . "
            WHERE a.stare = 'publicat' AND a.publicat_la <= NOW()
            ORDER BY a.publicat_la DESC
            LIMIT " . max(1, $limita)
        );
    }

    /** O pagina din lista de articole. */
    public static function pagina(int $numar = 1, ?string $categorieSlug = null): array
    {
        $numar  = max(1, $numar);
        $offset = ($numar - 1) * self::PE_PAGINA;

        $unde   = "WHERE a.stare = 'publicat' AND a.publicat_la <= NOW()";
        $params = [];
        if ($categorieSlug !== null) {
            $unde .= ' AND c.slug = ?';
            $params[] = $categorieSlug;
        }

        // LIMIT si OFFSET nu pot fi parametri legati in MySQL, deci sunt
        // convertiti la int inainte de interpolare. Nu accepta text.
        return Database::all(
            self::SELECT_PUBLIC . " {$unde}
             ORDER BY a.publicat_la DESC
             LIMIT " . self::PE_PAGINA . " OFFSET {$offset}",
            $params
        );
    }

    /** Cate articole publicate exista, optional intr-o categorie. */
    public static function numara(?string $categorieSlug = null): int
    {
        if ($categorieSlug === null) {
            return (int) Database::value(
                "SELECT COUNT(*) FROM articole WHERE stare = 'publicat' AND publicat_la <= NOW()"
            );
        }
        return (int) Database::value(
            "SELECT COUNT(*) FROM articole a
             JOIN categorii c ON c.id = a.categorie_id
             WHERE a.stare = 'publicat' AND a.publicat_la <= NOW() AND c.slug = ?",
            [$categorieSlug]
        );
    }

    public static function numarPagini(?string $categorieSlug = null): int
    {
        return max(1, (int) ceil(self::numara($categorieSlug) / self::PE_PAGINA));
    }

    /** Un articol publicat, dupa slug. */
    public static function dupaSlug(string $slug): ?array
    {
        return Database::one(
            self::SELECT_PUBLIC . "
            WHERE a.slug = ? AND a.stare = 'publicat' AND a.publicat_la <= NOW()",
            [$slug]
        );
    }

    /** Un articol indiferent de stare. Doar pentru admin si previzualizare. */
    public static function adminDupaId(int $id): ?array
    {
        return Database::one(self::SELECT_PUBLIC . ' WHERE a.id = ?', [$id]);
    }

    /** Toate articolele, inclusiv ciornele. Pentru lista din admin. */
    public static function adminToate(): array
    {
        return Database::all(
            self::SELECT_PUBLIC . ' ORDER BY COALESCE(a.publicat_la, a.creat_la) DESC'
        );
    }

    /** Articole din aceeasi categorie, fara cel curent. */
    public static function inrudite(array $articol, int $limita = 2): array
    {
        if (empty($articol['categorie_id'])) {
            return [];
        }
        return Database::all(
            self::SELECT_PUBLIC . "
            WHERE a.stare = 'publicat' AND a.publicat_la <= NOW()
              AND a.categorie_id = ? AND a.id <> ?
            ORDER BY a.publicat_la DESC
            LIMIT " . max(1, $limita),
            [$articol['categorie_id'], $articol['id']]
        );
    }

    /**
     * Un slug care nu se ciocneste cu altul existent.
     * Daca „titlu” e luat, incearca „titlu-2”, „titlu-3”, si asa mai departe.
     */
    public static function slugUnic(string $titlu, ?int $exceptaId = null): string
    {
        $baza = slugify($titlu) ?: 'articol';
        $slug = $baza;
        $n = 2;

        while (true) {
            $existent = Database::value(
                'SELECT id FROM articole WHERE slug = ?' . ($exceptaId ? ' AND id <> ?' : ''),
                $exceptaId ? [$slug, $exceptaId] : [$slug]
            );
            if ($existent === null) {
                return $slug;
            }
            $slug = $baza . '-' . $n++;
        }
    }

    public static function categorii(): array
    {
        return Database::all('SELECT * FROM categorii ORDER BY ordine, nume');
    }

    public static function categorieDupaSlug(string $slug): ?array
    {
        return Database::one('SELECT * FROM categorii WHERE slug = ?', [$slug]);
    }
}
