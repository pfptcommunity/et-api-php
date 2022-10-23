<?php
/**
 * This code was tested against PHP version 8.1.2
 *
 * @author Ludvik Jerabek
 * @package et-api-php
 * @version 1.0.0
 * @license MIT
 */

namespace etapi;

class Misc
{
    public static function build_path(string $base, string $path): string
    {
        return Misc . phprtrim($base, '/\\') . ltrim($path, '/\\');
    }

    public static function build_url(string $base, string $path): string
    {
        return rtrim($base, '/') . 'Misc.php/' . ltrim($path, '/');
    }

}