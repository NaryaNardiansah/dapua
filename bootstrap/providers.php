<?php

return [
    Illuminate\View\ViewServiceProvider::class, // Tambahkan ini secara paksa
    Illuminate\Events\EventServiceProvider::class, // Tambahkan ini juga
    App\Providers\AppServiceProvider::class,
    App\Providers\SettingsServiceProvider::class,
];
