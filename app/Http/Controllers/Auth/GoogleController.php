<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use GuzzleHttp\Client; // <-- Tambahkan ini

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        // Bypass SSL khusus untuk lokal
        $httpClient = new Client(['verify' => false]);

        return Socialite::driver('google')
            ->setHttpClient($httpClient) // <-- Tambahkan ini
            ->stateless()
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            // Bypass SSL khusus untuk lokal
            $httpClient = new Client(['verify' => false]);

            $googleUser = Socialite::driver('google')
                ->setHttpClient($httpClient) // <-- Tambahkan ini
                ->stateless()
                ->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                Auth::login($user);
            } else {
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(\Str::random(16)),
                    'email_verified_at' => now(),
                ]);
                Auth::login($newUser);
            }

            return redirect()->intended(route('dashboard'));
        } catch (Exception $e) {
            // Hapus dd() kalau sudah berhasil
            // dd($e->getMessage());
            return redirect()->route('login')->withErrors(['email' => 'Gagal login dengan Google: ' . $e->getMessage()]);
        }
    }
}
