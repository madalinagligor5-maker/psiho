<?php
declare(strict_types=1);

/**
 * Procesarea imaginilor incarcate din admin.
 *
 * Clienta incarca fotografii de 8 MB direct de pe telefon. Aici se intampla
 * tot ce trebuie ca asta sa nu fie o problema:
 *   - redimensionare la o latime maxima rezonabila pentru web
 *   - conversie la WebP (mai mic decat JPEG la aceeasi calitate)
 *   - stergerea metadatelor EXIF (locatie GPS, model de telefon)
 *
 * Foloseste extensia gd, prezenta implicit pe gazduirile cu cPanel.
 */
final class Imagine
{
    private const LATIME_MAX = 1600;
    private const CALITATE   = 82;

    /**
     * Proceseaza un fisier incarcat si returneaza numele fisierului WebP salvat.
     * Arunca RuntimeException cu un mesaj de aratat clientei daca ceva nu merge.
     */
    public static function proceseaza(array $fisier): string
    {
        if (($fisier['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new RuntimeException(self::mesajEroare((int) ($fisier['error'] ?? 0)));
        }

        // Verificam tipul real din continut, nu din extensia trimisa de browser.
        $info = @getimagesize($fisier['tmp_name']);
        if ($info === false) {
            throw new RuntimeException('Fișierul nu pare să fie o imagine.');
        }

        [$latime, $inaltime] = $info;
        $tip = $info[2];

        $sursa = match ($tip) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($fisier['tmp_name']),
            IMAGETYPE_PNG  => imagecreatefrompng($fisier['tmp_name']),
            IMAGETYPE_WEBP => imagecreatefromwebp($fisier['tmp_name']),
            IMAGETYPE_GIF  => imagecreatefromgif($fisier['tmp_name']),
            default => throw new RuntimeException('Format neacceptat. Folosește JPG, PNG sau WebP.'),
        };
        if ($sursa === false) {
            throw new RuntimeException('Imaginea nu a putut fi citită.');
        }

        // Redimensionare, pastrand proportiile, doar daca e mai lata decat maximul.
        if ($latime > self::LATIME_MAX) {
            $latimeNoua  = self::LATIME_MAX;
            $inaltimeNoua = (int) round($inaltime * (self::LATIME_MAX / $latime));

            $destinatie = imagecreatetruecolor($latimeNoua, $inaltimeNoua);
            // Pastreaza transparenta pentru PNG.
            imagealphablending($destinatie, false);
            imagesavealpha($destinatie, true);
            imagecopyresampled($destinatie, $sursa, 0, 0, 0, 0, $latimeNoua, $inaltimeNoua, $latime, $inaltime);
            imagedestroy($sursa);
        } else {
            $destinatie = $sursa;
        }

        // Nume unic, fara legatura cu numele original (care poate contine orice).
        $nume = date('Y/m/') . bin2hex(random_bytes(8)) . '.webp';
        $caleAbs = self::dirUploads() . '/' . $nume;

        if (!is_dir(dirname($caleAbs))) {
            mkdir(dirname($caleAbs), 0755, true);
        }

        // imagewebp reincodeaza de la zero, deci EXIF-ul original nu supravietuieste.
        if (!imagewebp($destinatie, $caleAbs, self::CALITATE)) {
            imagedestroy($destinatie);
            throw new RuntimeException('Conversia la WebP a eșuat.');
        }
        imagedestroy($destinatie);

        return $nume;
    }

    private static function dirUploads(): string
    {
        return dirname(__DIR__) . '/public_html/uploads';
    }

    private static function mesajEroare(int $cod): string
    {
        return match ($cod) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Fișierul e prea mare pentru server.',
            UPLOAD_ERR_PARTIAL => 'Încărcarea s-a întrerupt. Încearcă din nou.',
            UPLOAD_ERR_NO_FILE => 'Nu ai ales niciun fișier.',
            default => 'Încărcarea a eșuat. Încearcă din nou.',
        };
    }
}
