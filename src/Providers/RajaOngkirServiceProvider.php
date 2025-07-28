<?php

namespace Botble\RajaOngkir\Providers; // <-- HARUS SAMA dengan di plugin.json

use Illuminate\Support\ServiceProvider;

class RajaOngkirServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'rajaongkir');
    }

    public function register()
    {
        //
    }
}
