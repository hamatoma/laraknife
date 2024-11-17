<?php
namespace App\Helpers;

interface TranslatorHelper {
    public function trans(string $key): string;
    public function trans_choice(string $key, int $number): string;
}