<?php

namespace Tlemcen\Tlemcen\Livewire;
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
use Livewire\Component;
use Illuminate\Support\Facades\File;

class Topbarre1 extends Component
{
    
    public function mount()
    {
       
    }

    public function render()
    {
        return view('tlemcen::livewire.topbarre1');
    }
}