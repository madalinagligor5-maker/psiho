<?php
declare(strict_types=1);

/**
 * Convertor Markdown -> HTML.
 *
 * Scris de mana in loc sa adaugam o dependinta. Motivul: nu randam Markdown
 * arbitrar de pe internet, ci exact setul de noduri pe care editorul TipTap
 * din admin le poate produce. Setul e inchis si mic, deci 200 de linii ajung,
 * iar proiectul ramane cu zero dependinte PHP.
 *
 * Noduri suportate:
 *   # ## ###           titluri
 *   **gras**  *cursiv*  `cod`
 *   [text](url)        legaturi
 *   ![alt](url)        imagini
 *   - lista            lista neordonata
 *   1. lista           lista ordonata
 *   > citat            citat
 *   ---                linie de separare
 *   ```                bloc de cod
 *
 * Securitate: tot textul e escapat INAINTE de a genera HTML. Nu exista cale
 * prin care continut din baza de date sa ajunga ca marcaj executabil.
 */
final class Markdown
{
    public static function toHtml(string $md): string
    {
        $md = str_replace(["\r\n", "\r"], "\n", $md);

        // Blocurile de cod se scot din text inainte de orice altceva, ca sa nu
        // li se aplice regulile inline, si se pun la loc la final.
        $blocuri = [];
        $md = preg_replace_callback('/```[a-z]*\n(.*?)```/s', function ($m) use (&$blocuri) {
            $blocuri[] = '<pre><code>' . e($m[1]) . '</code></pre>';
            return "\x00BLOC" . (count($blocuri) - 1) . "\x00";
        }, $md) ?? $md;

        $html = [];
        $linii = explode("\n", $md);
        $nrLinii = count($linii);
        $i = 0;

        while ($i < $nrLinii) {
            $linie = $linii[$i];
            $taiata = trim($linie);

            // Linie goala: sare peste.
            if ($taiata === '') {
                $i++;
                continue;
            }

            // Bloc de cod deja extras.
            if (preg_match('/^\x00BLOC(\d+)\x00$/', $taiata, $m)) {
                $html[] = $blocuri[(int) $m[1]];
                $i++;
                continue;
            }

            // Linie de separare.
            if (preg_match('/^(---+|\*\*\*+)$/', $taiata)) {
                $html[] = '<hr>';
                $i++;
                continue;
            }

            // Titluri. Nu permitem h1 din continut: h1-ul paginii e titlul
            // articolului, iar doua h1-uri strica structura pentru cititoarele
            // de ecran si pentru SEO.
            if (preg_match('/^(#{1,6})\s+(.*)$/', $taiata, $m)) {
                $nivel = min(6, max(2, strlen($m[1]) + 1));
                $html[] = "<h{$nivel}>" . self::inline($m[2]) . "</h{$nivel}>";
                $i++;
                continue;
            }

            // Citat: aduna liniile consecutive care incep cu >.
            if (str_starts_with($taiata, '>')) {
                $bucati = [];
                while ($i < $nrLinii && str_starts_with(trim($linii[$i]), '>')) {
                    $bucati[] = ltrim(ltrim(trim($linii[$i]), '>'));
                    $i++;
                }
                $html[] = '<blockquote><p>' . self::inline(implode(' ', $bucati)) . '</p></blockquote>';
                continue;
            }

            // Lista neordonata.
            if (preg_match('/^[-*+]\s+/', $taiata)) {
                $elemente = [];
                while ($i < $nrLinii && preg_match('/^[-*+]\s+(.*)$/', trim($linii[$i]), $m)) {
                    $elemente[] = '<li>' . self::inline($m[1]) . '</li>';
                    $i++;
                }
                $html[] = '<ul>' . implode('', $elemente) . '</ul>';
                continue;
            }

            // Lista ordonata.
            if (preg_match('/^\d+\.\s+/', $taiata)) {
                $elemente = [];
                while ($i < $nrLinii && preg_match('/^\d+\.\s+(.*)$/', trim($linii[$i]), $m)) {
                    $elemente[] = '<li>' . self::inline($m[1]) . '</li>';
                    $i++;
                }
                $html[] = '<ol>' . implode('', $elemente) . '</ol>';
                continue;
            }

            // Paragraf: aduna pana la prima linie goala sau pana la un bloc nou.
            $bucati = [];
            while ($i < $nrLinii) {
                $curenta = trim($linii[$i]);
                if ($curenta === '' || self::incepeBlocNou($curenta)) {
                    break;
                }
                $bucati[] = $curenta;
                $i++;
            }
            if ($bucati !== []) {
                $html[] = '<p>' . self::inline(implode(' ', $bucati)) . '</p>';
            }
        }

        return implode("\n", $html);
    }

    /** O linie care ar incepe un bloc nou nu poate continua un paragraf. */
    private static function incepeBlocNou(string $linie): bool
    {
        return (bool) preg_match('/^(#{1,6}\s|[-*+]\s|\d+\.\s|>|---+$|\*\*\*+$|\x00BLOC\d+\x00$)/', $linie);
    }

    /**
     * Marcaj inline. Textul e escapat intai, deci orice HTML din sursa devine
     * text vizibil, nu marcaj.
     */
    private static function inline(string $text): string
    {
        $text = e($text);

        // Cod inline, primul: continutul lui nu primeste alte reguli.
        $coduri = [];
        $text = preg_replace_callback('/`([^`]+)`/', function ($m) use (&$coduri) {
            $coduri[] = '<code>' . $m[1] . '</code>';
            return "\x01" . (count($coduri) - 1) . "\x01";
        }, $text) ?? $text;

        // Imagini inaintea legaturilor: sintaxa lor incepe cu ! si altfel
        // ar fi prinsa de regula legaturilor.
        $text = preg_replace_callback(
            '/!\[([^\]]*)\]\(([^)\s]+)\)/',
            fn($m) => self::imagine($m[1], $m[2]),
            $text
        ) ?? $text;

        // Legaturi.
        $text = preg_replace_callback(
            '/\[([^\]]+)\]\(([^)\s]+)\)/',
            fn($m) => self::legatura($m[1], $m[2]),
            $text
        ) ?? $text;

        // Gras inaintea cursivului, altfel ** e citit ca doua *.
        $text = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text) ?? $text;
        $text = preg_replace('/(?<!\*)\*([^*]+)\*(?!\*)/', '<em>$1</em>', $text) ?? $text;

        // Ghilimele romanesti pentru citate simple scrise cu " ".
        $text = preg_replace('/&quot;([^&]*)&quot;/u', '„$1”', $text) ?? $text;

        // Codurile inline inapoi la locul lor.
        $text = preg_replace_callback('/\x01(\d+)\x01/', fn($m) => $coduri[(int) $m[1]], $text) ?? $text;

        return $text;
    }

    /** Legatura, cu protectie impotriva schemelor periculoase. */
    private static function legatura(string $text, string $url): string
    {
        if (!self::urlSigur($url)) {
            return $text;
        }
        // Legaturile externe se deschid in fila noua si primesc noopener:
        // fara el, pagina tinta poate manipula fereastra care a deschis-o.
        $extern = preg_match('#^https?://#i', $url)
            && !str_starts_with($url, (string) config('site', 'url'));

        $atribute = $extern ? ' target="_blank" rel="noopener noreferrer"' : '';
        return '<a href="' . $url . '"' . $atribute . '>' . $text . '</a>';
    }

    private static function imagine(string $alt, string $url): string
    {
        if (!self::urlSigur($url)) {
            return '';
        }
        // loading="lazy" pentru imaginile din corpul articolului: sunt mereu
        // sub prima parte vizibila a paginii.
        return '<img src="' . $url . '" alt="' . $alt . '" loading="lazy" decoding="async">';
    }

    /** Acceptam doar http, https, mailto si cai relative. */
    private static function urlSigur(string $url): bool
    {
        $url = trim($url);
        if ($url === '') {
            return false;
        }
        if (str_starts_with($url, '/') || str_starts_with($url, '#')) {
            return true;
        }
        return (bool) preg_match('#^(https?://|mailto:)#i', $url);
    }

    /**
     * Text simplu dintr-un Markdown, pentru rezumate si meta descrieri.
     */
    public static function toText(string $md, int $maxim = 0): string
    {
        $text = preg_replace('/```.*?```/s', '', $md) ?? $md;
        $text = preg_replace('/!\[[^\]]*\]\([^)]*\)/', '', $text) ?? $text;
        $text = preg_replace('/\[([^\]]+)\]\([^)]*\)/', '$1', $text) ?? $text;
        $text = preg_replace('/[#>*`_-]/', '', $text) ?? $text;
        $text = trim(preg_replace('/\s+/u', ' ', $text) ?? $text);

        if ($maxim > 0 && mb_strlen($text) > $maxim) {
            $text = mb_substr($text, 0, $maxim);
            $ultimSpatiu = mb_strrpos($text, ' ');
            if ($ultimSpatiu !== false) {
                $text = mb_substr($text, 0, $ultimSpatiu);
            }
            $text .= '…';
        }

        return $text;
    }
}
