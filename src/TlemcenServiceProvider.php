<?php

namespace Tlemcen\Tlemcen;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class TlemcenServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {

    }

   
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views','tlemcen');
        Livewire::component('tlemcen1', \Tlemcen\Tlemcen\Livewire\Tlemcen1::class);
        Livewire::component('tlemcen2', \Tlemcen\Tlemcen\Livewire\Tlemcen2::class);
        Livewire::component('tlemcen3', \Tlemcen\Tlemcen\Livewire\Tlemcen3::class);
        Livewire::component('tlemcen4', \Tlemcen\Tlemcen\Livewire\Tlemcen4::class);

        Livewire::component('tlemcen.topbarre1', \Tlemcen\Tlemcen\Livewire\Topbarre1::class);
        Livewire::component('tlemcen.topbarre2', \Tlemcen\Tlemcen\Livewire\Topbarre2::class);
        Livewire::component('tlemcen.topbarre3', \Tlemcen\Tlemcen\Livewire\Topbarre3::class);
        Livewire::component('tlemcen.petitrdv', \Tlemcen\Tlemcen\Livewire\Petitrdv::class);

        Livewire::component('tlemcen.navbarre', \Tlemcen\Tlemcen\Livewire\Navbarre::class);



    }
}
