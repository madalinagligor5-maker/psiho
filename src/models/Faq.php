<?php
declare(strict_types=1);

final class Faq
{
    /** Intrebarile active. `$doarAcasa` filtreaza la cele marcate pentru prima pagina. */
    public static function toate(bool $doarAcasa = false): array
    {
        $unde = 'WHERE activ = 1' . ($doarAcasa ? " AND afisare = 'acasa'" : '');
        return Database::all("SELECT * FROM faq {$unde} ORDER BY ordine, id");
    }

    /** Inclusiv cele dezactivate. Pentru admin. */
    public static function adminToate(): array
    {
        return Database::all('SELECT * FROM faq ORDER BY ordine, id');
    }
}
