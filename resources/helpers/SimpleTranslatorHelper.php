<?php
namespace App\Helpers;

class SimpleTranslatorHelper implements TranslatorHelper {
    public function trans(string $key): string{
        return $key;
    }
    public function trans_choice(string $key, int $number): string{
        $rc = $number == 1 ? $key : "{$key}s";
        return $rc;
    }

}