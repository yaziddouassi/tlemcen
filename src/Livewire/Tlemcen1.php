<?php

namespace Tlemcen\Tlemcen\Livewire;
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
use Livewire\Component;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon ;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Collection;

class Tlemcen1 extends Component
{
    
    public function mount()
    {
       
    }


    public function render()
    {
        return view('tlemcen::livewire.tlemcen1')
        ->layout('tlemcen::layouts.app');
    }
}