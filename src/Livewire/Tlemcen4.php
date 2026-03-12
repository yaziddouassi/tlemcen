<?php

namespace Tlemcen\Tlemcen\Livewire;

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Tlemcen\Tlemcen\Models\RendezvousJouractif;
use Tlemcen\Tlemcen\Models\RendezvousClient;
use Illuminate\Support\Facades\Auth;

class Tlemcen4 extends Component
{
    use WithPagination;

    public $prenom = '';
    public $telephone = '';
    public $adresse = '';

    protected $queryString = []; // 🔥 AUCUNE query dans l'URL

    public $currentdate;

    /**
     * Initialisation
     */
    public function mount()
    {
        $this->currentdate = Carbon::now()->format('Y-n-j');
        // sécurité au reload
         if (!empty($_SERVER['QUERY_STRING'])) {
        $url = strtok($_SERVER["REQUEST_URI"], '?'); // retire tout après le ?
        $this->redirect($url); // Livewire redirect propre
    }
    }

  
    public function updatedCurrentdate()
    {
        $this->resetPage();
    }

   public function valider()
    {

     $this->validate([
         'prenom' => ['required'],
         'telephone' => ['required'],
         'adresse' => ['required'],
       ]);

     $client = new RendezvousClient();

     $client->user_id =  Auth::user()->id;
     $client->usernom =  Auth::user()->name;
     $client->userprenom = $this->prenom ;
     $client->usertelephone = $this->telephone ;
     $client->usermail =  Auth::user()->email;
     $client->useradresse = $this->adresse ;
     $client->save()  ;

     $this->js("
               Swal.fire({
                 title: 'Bravo!',
                 text: 'le formulaire ',
                 icon: 'success',
                 confirmButtonText: 'valider'
                               })
                           ");
     
    }

    public function render()
    {

        return view('tlemcen::livewire.tlemcen4', [
            'lesjours' => RendezvousJouractif::where('ladate', '>=', $this->currentdate)
                       ->where('nbheuredispo', '>', 0)
                       ->where('status', '=', 'oui')
                       ->orderBy('ladate')
                       ->paginate(10),
            'client' => RendezvousClient::where('user_id',Auth::user()->id)
                          ->first()
        ])->layout('tlemcen::layouts.app');
    }
}

