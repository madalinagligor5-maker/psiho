<?php
declare(strict_types=1);

/**
 * Exportul de continut — ieșirea de siguranță (§3.3).
 *
 * Fiecare articol e scris ca fisier .md cu antet YAML in content-export/.
 * Se ruleaza la fiecare publicare si manual, dintr-un buton in admin.
 *
 * Rostul: peste doi ani, daca acest cod e abandonat, continutul se muta oriunde
 * intr-o dupa-amiaza — Markdown cu front matter e formatul pe care il citeste
 * orice generator de site static. Nu depinde de un dump SQL si de o schema pe
 * care nu si-o mai aminteste nimeni.
 */
final class Export
{
    private static function dir(): string
    {
        $dir = dirname(__DIR__) . '/content-export';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    /** Scrie un singur articol. Returneaza calea fisierului. */
    public static function articol(array $a): string
    {
        $categorie = '';
        if (!empty($a['categorie_id'])) {
            $categorie = (string) Database::value(
                'SELECT slug FROM categorii WHERE id = ?', [$a['categorie_id']]
            );
        }

        // Antetul YAML. Valorile de text sunt puse intre ghilimele si au
        // ghilimelele interioare escapate, ca sa nu strice YAML-ul.
        $meta = [
            'title'       => $a['titlu'],
            'slug'        => $a['slug'],
            'category'    => $categorie,
            'excerpt'     => $a['rezumat'],
            'status'      => $a['stare'],
            'published_at'=> $a['publicat_la'] ?? '',
            'updated_at'  => $a['actualizat_la'] ?? '',
            'meta_description' => $a['meta_descriere'],
            'cover_image' => $a['imagine'] ?? '',
        ];

        $yaml = "---\n";
        foreach ($meta as $cheie => $valoare) {
            $yaml .= $cheie . ': ' . self::yamlValoare((string) $valoare) . "\n";
        }
        $yaml .= "---\n\n";

        $continut = $yaml . rtrim($a['continut']) . "\n";

        $cale = self::dir() . '/' . $a['slug'] . '.md';
        file_put_contents($cale, $continut);

        return $cale;
    }

    /** Reexporta tot. Apelat din butonul manual. Returneaza numarul de fisiere. */
    public static function toate(): int
    {
        $articole = Database::all('SELECT * FROM articole ORDER BY id');
        foreach ($articole as $a) {
            self::articol($a);
        }
        return count($articole);
    }

    /** Sterge fisierul .md al unui articol care a fost sters din baza. */
    public static function stergeArticol(string $slug): void
    {
        $cale = self::dir() . '/' . $slug . '.md';
        if (is_file($cale)) {
            unlink($cale);
        }
    }

    private static function yamlValoare(string $v): string
    {
        if ($v === '') {
            return '""';
        }
        return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $v) . '"';
    }
}
