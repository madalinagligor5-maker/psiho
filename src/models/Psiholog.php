<?php
declare(strict_types=1);

/**
 * Psihologii cabinetului și specializările lor.
 *
 * Datele sunt publice și verificabile (cod CPR pe verificapsiholog.ro). Regimul
 * fiecărei specializări — autonom sau sub supervizare — se afișează corect și
 * onest; niciodată nu se sugerează practică autonomă pe o specializare aflată în
 * supervizare.
 */
final class Psiholog
{
    /** Toți psihologii activi, în ordine. */
    public static function toti(): array
    {
        return Database::all('SELECT * FROM psihologi WHERE activ = 1 ORDER BY ordine, id');
    }

    public static function dupaSlug(string $slug): ?array
    {
        return Database::one('SELECT * FROM psihologi WHERE slug = ? AND activ = 1', [$slug]);
    }

    public static function dupaId(int $id): ?array
    {
        return Database::one('SELECT * FROM psihologi WHERE id = ?', [$id]);
    }

    /** Specializările unui psiholog, cu nivel și regim. */
    public static function specializari(int $psihologId): array
    {
        return Database::all(
            'SELECT * FROM psihologi_specializari WHERE psiholog_id = ? ORDER BY ordine, id',
            [$psihologId]
        );
    }

    /** Eticheta lizibilă a regimului, pentru afișare. */
    public static function regimText(string $regim): string
    {
        return $regim === 'autonom' ? 'regim autonom' : 'sub supervizare';
    }

    /** Pentru dropdown-ul de preferință din formularul de contact. */
    public static function pentruSelect(): array
    {
        return Database::all('SELECT id, nume FROM psihologi WHERE activ = 1 ORDER BY ordine, id');
    }

    /** Toți psihologii, inclusiv inactivi, cu specializările atașate. Pentru admin. */
    public static function adminToti(): array
    {
        $psihologi = Database::all('SELECT * FROM psihologi ORDER BY ordine, id');
        foreach ($psihologi as &$p) {
            $p['specializari'] = self::specializari((int) $p['id']);
        }
        return $psihologi;
    }

    /** Actualizează câmpurile de bază ale unui psiholog. */
    public static function actualizeaza(int $id, array $c): void
    {
        Database::run(
            'UPDATE psihologi SET nume=?, titlu_scurt=?, cod_cpr=?, judet=?, filiala=?, bio=?, ordine=?, activ=?
             WHERE id=?',
            [$c['nume'], $c['titlu_scurt'], $c['cod_cpr'], $c['judet'], $c['filiala'], $c['bio'],
             (int) $c['ordine'], (int) $c['activ'], $id]
        );
    }

    /**
     * Rescrie specializările unui psiholog: șterge-le pe cele vechi și le pune
     * pe cele noi. Simplu și corect — nu urmărim id-uri individuale.
     */
    public static function scrieSpecializari(int $psihologId, array $randuri): void
    {
        Database::run('DELETE FROM psihologi_specializari WHERE psiholog_id = ?', [$psihologId]);
        $ordine = 1;
        foreach ($randuri as $r) {
            $nume = trim((string) ($r['nume'] ?? ''));
            if ($nume === '') {
                continue;
            }
            $regim = ($r['regim'] ?? 'supervizare') === 'autonom' ? 'autonom' : 'supervizare';
            Database::run(
                'INSERT INTO psihologi_specializari (psiholog_id, nume, nivel, regim, ordine) VALUES (?, ?, ?, ?, ?)',
                [$psihologId, $nume, trim((string) ($r['nivel'] ?? '')), $regim, $ordine++]
            );
        }
    }
}
