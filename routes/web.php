<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
   
   Route::get('/admin/rendez-vous',\Tlemcen\Tlemcen\Livewire\Tlemcen1::class);
   Route::get('/admin/rendez-vous/{madate}/journee',\Tlemcen\Tlemcen\Livewire\Tlemcen2::class);
   Route::get('/admin/rendez-vous/{madate}/mois',\Tlemcen\Tlemcen\Livewire\Tlemcen3::class);
   Route::get('/rendez-vous',\Tlemcen\Tlemcen\Livewire\Tlemcen4::class);
});