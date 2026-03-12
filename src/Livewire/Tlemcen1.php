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
    use WithPagination;
    public $search = '';

      public function logout()
{
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
}

    public function mount()
    {
       
    }

    public function annulerSearch() {
        $this->search = '';
    }


    public function render()
    {

         if($this->search == '') {
           $users = \Tlemcen\Tlemcen\Models\RendezvousClient::paginate(4);
        }

        if($this->search != '') {
           $users = \Tlemcen\Tlemcen\Models\RendezvousClient::where('usernom', 'like', '%'.$this->search.'%')
                                      ->orWhere('userprenom', 'like', '%'.$this->search.'%')
                                     ->paginate(4);
        }


        return view('tlemcen::livewire.tlemcen1',
                     ['users' => $users])
        ->layout('tlemcen::layouts.app');
    }
}