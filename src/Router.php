<?php
declare(strict_types=1);

/**
 * Router minimal, cu potrivire pe tipare.
 *
 * Tiparele folosesc {nume} pentru un segment de cale, care ajunge in handler
 * ca argument. Fara regex scris de mana in definitia rutelor:
 *
 *   $router->get('/articol/{slug}', fn($slug) => ...);
 *
 * Un {nume} se potriveste cu orice in afara de slash.
 */
final class Router
{
    /** @var array<string, array<array{tipar: string, handler: callable}>> */
    private array $rute = ['GET' => [], 'POST' => []];

    private mixed $handler404 = null;

    public function get(string $tipar, callable $handler): void
    {
        $this->rute['GET'][] = ['tipar' => $tipar, 'handler' => $handler];
    }

    public function post(string $tipar, callable $handler): void
    {
        $this->rute['POST'][] = ['tipar' => $tipar, 'handler' => $handler];
    }

    public function notFound(callable $handler): void
    {
        $this->handler404 = $handler;
    }

    /**
     * Gaseste ruta potrivita si o executa.
     * Rutele se verifica in ordinea in care au fost inregistrate, deci cele
     * specifice trebuie declarate inaintea celor cu parametri.
     */
    public function dispatch(string $metoda, string $cale): void
    {
        // Taie query string-ul si slash-ul final, dar pastreaza "/" pentru acasa.
        $cale = parse_url($cale, PHP_URL_PATH) ?: '/';
        $cale = rtrim($cale, '/');
        if ($cale === '') {
            $cale = '/';
        }
        $cale = rawurldecode($cale);

        foreach ($this->rute[$metoda] ?? [] as $ruta) {
            $argumente = $this->potriveste($ruta['tipar'], $cale);
            if ($argumente !== null) {
                ($ruta['handler'])(...$argumente);
                return;
            }
        }

        http_response_code(404);
        if ($this->handler404 !== null) {
            ($this->handler404)();
        }
    }

    /**
     * Returneaza valorile parametrilor daca tiparul se potriveste, altfel null.
     * Un array gol inseamna potrivire fara parametri — de aceea null si nu [].
     */
    private function potriveste(string $tipar, string $cale): ?array
    {
        if (!str_contains($tipar, '{')) {
            return $tipar === $cale ? [] : null;
        }

        // Escapam intai tot tiparul, apoi inlocuim {nume} — care dupa escapare
        // arata ca \{nume\} — cu grupul de captura.
        $regex = preg_quote($tipar, '#');
        $regex = preg_replace('/\\\\\{[a-z_]+\\\\\}/', '([^/]+)', $regex);

        if (!preg_match('#^' . $regex . '$#u', $cale, $potriviri)) {
            return null;
        }

        array_shift($potriviri); // primul element e potrivirea completa
        return $potriviri;
    }
}
