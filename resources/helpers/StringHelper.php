<?php
namespace App\Helpers;

class StringHelper{
    /**
     * Creates a good random password: uppercase, lowercase, digits, specials.
     * @param int $length the length of the result
     * @return string a random password, e.g. "A.LESY443re/mik"
     */
    public static function createPassword(int $length=16): string{
        $lengh = max(6, $length);
        $vocals = 'aeiouyaeiouyaeiouyaeiouy';
        $consonants = 'bcfghjlmnpqrstvwxzbcfghjlmnpqrstvwxzbcfghjlmnpqrstvwxz';
        $specials = './=+-;,./=+-;,./=+-;,';
        $sources = [$vocals, $consonants, $specials];
        $even = false;
        $rc = '';
        $count = $length - 3 - 2;
        for ($ix = 0; $ix < $count; $ix++){
            $cc = StringHelper::randomChar($ix % 2 == 0 ? $vocals : $consonants);
            if ($ix < $count / 2){
                $cc = strtoupper($cc);
            }
            $rc .= $cc;
        }
        for ($ix = 0; $ix < 3; $ix++){
            if ($ix === 2){
                $cc = sprintf('%03d', rand(0, 999));
            } else {
                $cc = StringHelper::randomChar($specials);
            }
            $pos = rand(0, strlen($rc));
            if ($pos === 0){
                $rc = $cc . $rc;
            } else {
                $rc = substr($rc, 0, $pos) .  $cc . substr($rc, $pos);
            }
        }
        return $rc;
    }
    public static function randomChar(string $charSet){
        $rc = $charSet[rand(0, strlen($charSet) - 1)];
        return $rc;
    }
    public static function singularOf(string $word){
        if (str_ends_with($word, 'ies')){
            $rc = substr($word, 0, strlen($word) - 3);
        } elseif (str_ends_with($word, 's')){
            $rc = substr($word, 0, strlen($word) - 1);
        } else {
            $rc = $word;
        }
        return $rc;
    }
    /**
     * Converts the $string into a capital string: the first character will be uppercase.
     */
    public static function toCapital($string): string{
        if (empty($string)){
            $rc = '';
        } else {
            $rc = strtoupper(substr($string, 0, 1)) . substr($string, 1);
        }
        return $rc;
    }
}