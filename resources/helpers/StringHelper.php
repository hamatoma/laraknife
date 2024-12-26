<?php
namespace App\Helpers;

class StringHelper
{
    public static $charSetAlphaNumeric = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz01234567890_";
    /**
     * Creates a good random password: uppercase, lowercase, digits, specials.
     * @param int $length the length of the result
     * @return string a random password, e.g. "A.LESY443re/mik"
     */
    public static function createPassword(int $length = 16): string
    {
        $lengh = max(6, $length);
        $vocals = 'aeiouyaeiouyaeiouyaeiouy';
        $consonants = 'bcfghjlmnpqrstvwxzbcfghjlmnpqrstvwxzbcfghjlmnpqrstvwxz';
        $specials = './=+-;,./=+-;,./=+-;,';
        $sources = [$vocals, $consonants, $specials];
        $even = false;
        $rc = '';
        $count = $length - 3 - 2;
        for ($ix = 0; $ix < $count; $ix++) {
            $cc = StringHelper::randomChar($ix % 2 == 0 ? $vocals : $consonants);
            if ($ix < $count / 2) {
                $cc = strtoupper($cc);
            }
            $rc .= $cc;
        }
        for ($ix = 0; $ix < 3; $ix++) {
            if ($ix === 2) {
                $cc = sprintf('%03d', rand(0, 999));
            } else {
                $cc = StringHelper::randomChar($specials);
            }
            $pos = rand(0, strlen($rc));
            if ($pos === 0) {
                $rc = $cc . $rc;
            } else {
                $rc = substr($rc, 0, $pos) . $cc . substr($rc, $pos);
            }
        }
        return $rc;
    }
    /**
     * Converts a string into an assoziative array. Inversion of implodeAssoc().
     * @param string $source the string to convert
     * @param string $separatorLine the separator between the pair entries
     * @param string $separatorPair the separator between key and value
     * @param array $array the array to convert
     */
    public static function explodeAssoc(string $source, string $separatorLine = "\n", string $separatorPair = '='): array
    {
        $rc = [];
        $pos = 0;
        while (($end = strpos($source, $separatorLine, $pos)) !== false) {
            if (($keyEnd = strpos($source, $separatorPair, $pos)) === false) {
                $rc[substr($source, $pos, $end)] = '';
            } else {
                $key = substr($source, $pos, $keyEnd - $pos);
                $value = substr($source, $keyEnd + 1, $keyEnd - $pos - 1);
                $rc[$key] = $value;
            }
            $pos = $end + 1;
        }
        if ($pos < strlen($source) - 1) {
            if (($keyEnd = strpos($source, $separatorPair, $pos)) === false) {
                $rc[substr($source, $pos, $end)] = '';
            } else {
                $key = substr($source, $pos, $keyEnd - $pos);
                $value = substr($source, $keyEnd + 1, $keyEnd - $pos - 1);
                $rc[$key] = $value;
            }
        }
        return $rc;
    }

    /**
     * Converts an assoziative array into a string.
     * @param array $array the array to convert
     * @param string $separatorLine the separator between the pair entries
     * @param string $separatorPair the separator between key and value
     * @return string the serialized array
     */
    public static function implodeAssoc(array &$array, string $separatorLine = "\n", string $separatorPair = '=', bool $ignoreMeta = true): string
    {
        $rc = '';
        foreach ($array as $key => $value) {
            if ($ignoreMeta && ($key[0] === '_' || str_starts_with($key, 'btn'))) {
                continue;
            } else {
                if ($rc === '') {
                    $rc = "$key$separatorPair$value";
                } else {
                    $rc .= "$separatorLine$key$separatorPair$value";
                }
            }
        }
        return $rc;
    }
    public static function randomChar(string $charSet): string
    {
        $rc = $charSet[rand(0, strlen($charSet) - 1)];
        return $rc;
    }
    public static function randomString(string $charSet, int $length): string
    {
        $rc = '';
        $max = strlen($charSet) - 1;
        for ($ix = 0; $ix < $length; $ix++) {
            $rc .= $charSet[rand(0, $max)];
        }
        return $rc;
    }
    /**
     * Replaces or adds an option into a data block.
     * Format of the data block: an option starts with the $optionName and a colon.
     * Note: the option entry already starts with a "\n" (for efficience).
     * @param string $data the textblock to inspect
     * @param string $optionName: the prefix of the wanted line
     * @param string $value: the value of the option to store
     */
    public static function singularOf(string $word)
    {
        if (str_ends_with($word, 'ies')) {
            $rc = substr($word, 0, strlen($word) - 3);
        } elseif (str_ends_with($word, 's')) {
            $rc = substr($word, 0, strlen($word) - 1);
        } else {
            $rc = $word;
        }
        return $rc;
    }
    /**
     * Converts a string into a word usable as part of an URL.
     * @param null|string $text the text to convert
     * @param null|int $maxLength the result is never longer than that
     * @return string a word usable as part of the string
     */
    public static function textToUrl(?string $text, ?int $maxLength = null): string
    {
        if ($text == null) {
            $rc = '';
        } else {
            $rc = preg_replace(['/[^\w.+-]+/', '/__+/'], ['_', '_'], $text);
            if ($maxLength != null && strlen($rc) > $maxLength) {
                $rc = substr($rc, $maxLength);
            }
            $rc = strtolower($rc);
        }
        return $rc;
    }
    /**
     * Converts the $string into a capital string: the first character will be uppercase.
     */
    public static function toCapital($string): string
    {
        if (empty($string)) {
            $rc = '';
        } else {
            $rc = strtoupper(substr($string, 0, 1)) . substr($string, 1);
        }
        return $rc;
    }
}