<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoDisposableEmail implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $domain = strtolower(substr(strrchr($value, "@"), 1));
        $disposableDomains = config('disposable_emails.domains', []);

        if (in_array($domain, $disposableDomains)) {
            $fail('Pendaftaran ditolak. Harap gunakan alamat email yang valid dan permanen (bukan email sementara).');
        }
    }
}
