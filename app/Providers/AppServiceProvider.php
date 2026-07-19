<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifikasi Email - Lumina Library')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Klik tombol di bawah untuk memverifikasi alamat email kamu.')
                ->action('Verifikasi Email Sekarang', $url)
                ->line('Link ini akan kadaluarsa dalam 60 menit.')
                ->line('Jika kamu tidak merasa mendaftar, abaikan email ini.');
        });

        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds        = config('midtrans.is_3ds');
    }
}