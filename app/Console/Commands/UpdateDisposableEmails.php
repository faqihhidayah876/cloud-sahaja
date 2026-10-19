<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class UpdateDisposableEmails extends Command
{
    protected $signature = 'emails:update-blocklist';
    protected $description = 'Update daftar domain temporary email dari sumber publik (GitHub)';

    public function handle()
    {
        $this->info('Mengambil daftar domain terbaru dari GitHub...');

        $url = 'https://raw.githubusercontent.com/disposable-email-domains/disposable-email-domains/master/disposable_email_blocklist.conf';

        try {
            $response = Http::timeout(30)->get($url);

            if ($response->successful()) {
                $domains = explode("\n", trim($response->body()));
                $domains = array_filter($domains);
                $domains = array_map('trim', $domains);

                // Tambahan domain spesifik yang perlu dipastikan selalu masuk
                $customDomains = [
                    'ensproxy.com',
                    'yanemail.com',
                    'mail.yanemail.xyz',
                    'oemails.com',
                    'gmail10p.com',
                    'tempmail.io',
                    'tempmail.plus',
                    'tempmailo.com',
                    'tempail.com',
                    'emailnator.com',
                    'inboxes.com',
                    'dropmail.me',
                    'mail.tm',
                    'mail.gw',
                    'cock.li',
                    'anonaddy.me',
                    'anonaddy.com',
                ];

                $domains = array_unique(array_merge($domains, $customDomains));

                // Whitelist domain yang TIDAK BOLEH masuk blacklist (email publik umum & institusi)
                $whitelist = [
                    'gmail.com',
                    'yahoo.com',
                    'ymail.com',
                    'rocketmail.com',
                    'outlook.com',
                    'hotmail.com',
                    'live.com',
                    'msn.com',
                    'icloud.com',
                    'me.com',
                    'mac.com',
                    'aol.com',
                    'zoho.com',
                    'proton.me',
                    'protonmail.com',
                    'tutanota.com',
                    'tuta.io',
                ];

                $filteredDomains = [];
                foreach ($domains as $domain) {
                    $domainLower = strtolower(trim($domain));
                    if (empty($domainLower) || $domainLower[0] === '#') {
                        continue;
                    }

                    // Abaikan jika ada di whitelist
                    if (in_array($domainLower, $whitelist)) {
                        continue;
                    }

                    // Abaikan domain sekolah/kampus (.edu, .ac.id, dll)
                    if (str_ends_with($domainLower, '.edu') || str_ends_with($domainLower, '.ac.id') || str_ends_with($domainLower, '.edu.sg')) {
                        continue;
                    }

                    $filteredDomains[] = $domainLower;
                }

                $filteredDomains = array_unique($filteredDomains);
                sort($filteredDomains);

                $configPath = config_path('disposable_emails.php');

                $content = "<?php\n\nreturn [\n    /*\n    |--------------------------------------------------------------------------\n    | Disposable Email Domains\n    |--------------------------------------------------------------------------\n    |\n    | Daftar domain email sementara (temporary/throwaway email) yang dilarang\n    | digunakan untuk registrasi.\n    |\n    */\n\n    'domains' => [\n";
                foreach ($filteredDomains as $domain) {
                    $content .= "        '{$domain}',\n";
                }
                $content .= "    ],\n];\n";

                File::put($configPath, $content);

                $this->info('Berhasil! ' . count($filteredDomains) . ' domain telah diperbarui.');
                $this->info('File: config/disposable_emails.php');
            } else {
                $this->error('Gagal mengambil data. HTTP Status: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
