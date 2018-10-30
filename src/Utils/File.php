<?php

namespace BitWasp\Groestlcoind\Utils;

use BitWasp\Groestlcoind\Exception\GroestlcoindException;

class File
{
    public static function recursiveDelete(string $src)
    {
        if (!is_dir($src)) {
            throw new GroestlcoindException("Parameter 1 for recursiveDelete should be a directory");
        }

        $dir = opendir($src);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    self::recursiveDelete($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }
}
