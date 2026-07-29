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
}
