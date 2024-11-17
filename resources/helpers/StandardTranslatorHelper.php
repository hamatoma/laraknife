<?php
namespace App\Helpers;

class StandardTranslatorHelper implements TranslatorHelper {
    public function trans(string $key): string{
        return __($key);
    }
    public function trans_choice(string $key, int $number): string{
        $rc = trans_choice($key, $number);
        return $rc;
    }
}