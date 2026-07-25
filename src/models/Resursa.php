<?php
declare(strict_types=1);

final class Resursa
{
    public static function toate(): array
    {
        return Database::all('SELECT * FROM resurse WHERE activ = 1 ORDER BY ordine, id');
    }

    public static function adminToate(): array
    {
        return Database::all('SELECT * FROM resurse ORDER BY ordine, id');
    }
}
