<?php
declare(strict_types=1);

/**
 * Invelis subtire peste PDO.
 *
 * Nu e un ORM si nu vrea sa fie. Rolul lui e sa faca varianta corecta —
 * interogari pregatite, cu parametri legati — mai scurt de scris decat
 * varianta gresita. Nicaieri in acest proiect nu se concateneaza valori
 * intr-un SQL.
 */
final class Database
{
    private static ?PDO $pdo = null;

    /** Conexiunea, creata la prima folosire. */
    public static function pdo(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $c = config('db');
        $dsn = "mysql:host={$c['host']};dbname={$c['name']};charset={$c['charset']}";

        try {
            self::$pdo = new PDO($dsn, $c['user'], $c['pass'], [
                // Erorile devin exceptii. Fara asta, o interogare gresita
                // returneaza tacut false si bug-ul apare trei ecrane mai incolo.
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // Interogari pregatite reale, nu emulate de driver.
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            // Mesajul PDO contine datele de conectare. Nu ajunge la vizitator.
            error_log('Conexiune baza de date esuata: ' . $e->getMessage());
            http_response_code(503);
            exit('Site indisponibil momentan. Revino peste cateva minute.');
        }

        return self::$pdo;
    }

    /** Ruleaza o interogare cu parametri si returneaza statement-ul. */
    public static function run(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /** Toate randurile. */
    public static function all(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll();
    }

    /** Primul rand, sau null. */
    public static function one(string $sql, array $params = []): ?array
    {
        $rand = self::run($sql, $params)->fetch();
        return $rand === false ? null : $rand;
    }

    /** O singura valoare din primul rand, sau null. */
    public static function value(string $sql, array $params = []): mixed
    {
        $val = self::run($sql, $params)->fetchColumn();
        return $val === false ? null : $val;
    }

    /** INSERT care returneaza id-ul randului nou. */
    public static function insert(string $sql, array $params = []): int
    {
        self::run($sql, $params);
        return (int) self::pdo()->lastInsertId();
    }

    /** Numarul de randuri afectate de UPDATE sau DELETE. */
    public static function affected(string $sql, array $params = []): int
    {
        return self::run($sql, $params)->rowCount();
    }
}
