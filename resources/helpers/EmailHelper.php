<?php
namespace App\Helpers;

use App\Helpers\ViewHelper;
use App\Mail\ForgottenPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailables\Address;

class EmailHelper
{
    public static function sendMail(string $name, string $to, array $snippets)
    {
        switch ($name) {
            case 'user.forgotten':
                Mail::to($to)->send(
                    new ForgottenPassword($snippets['link'])
                );
                break;
            default:
                break;
        }
    }
}
