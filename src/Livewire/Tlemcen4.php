<?php

namespace Tlemcen\Tlemcen\Livewire;

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Tlemcen\Tlemcen\Models\RendezvousJouractif;

class Tlemcen4 extends Component
{
    use WithPagination;

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

    /**
     * Reset pagination quand la date change
     */
    public function updatedCurrentdate()
    {
        $this->resetPage();
    }

    /**
     * Rendu
     */
    public function render()
    {
        return view('tlemcen::livewire.tlemcen4', [
            'lesjours' => RendezvousJouractif::where('ladate', '>=', $this->currentdate)
                       ->where('nbheuredispo', '>', 0)
                       ->where('status', '=', 'oui')
                       ->orderBy('ladate')
                       ->paginate(10)
        ])->layout('tlemcen::layouts.app');
    }
}

