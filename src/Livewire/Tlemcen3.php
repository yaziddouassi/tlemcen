<?php

namespace Tlemcen\Tlemcen\Livewire;
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
use Livewire\Component;
use Illuminate\Support\Facades\File;
use Tlemcen\Tlemcen\Utils\Rdvous;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon ;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Collection;
use Tlemcen\Tlemcen\Models\RendezvousHoraire ;
use Tlemcen\Tlemcen\Models\RendezvousJouractif ;

class Tlemcen3 extends Component
{
    public $jour ;
    public $mois ;
    public $annee ;
    public $journee ;
    public $madate ;
    public $ladate ;
    public $currentdate;
    public $lesjours;

    public function mount($madate)
    {
        $rdvous = new Rdvous() ;

        $rdvous->initier($madate);

        $this->currentdate = $madate;

        $this->jour = $rdvous->getJour() ;
        $this->mois = $rdvous->getMois();
        $this->annee = $rdvous->getAnnee() ;
        $this->journee = $rdvous->getJournee() ;
        $this->madate = $madate;
       
        $this->ladate=  Carbon::create($this->annee,$this->mois ,$this->jour,23,59,59, 'Europe/Paris' );

        $this->lesjours = RendezvousJouractif::where('annee',$this->annee)
                                   ->where('mois',$this->mois)
                                   ->where('status','oui')
                                   ->orderBy('jour')
                                   ->get() ; 

        
    }


    public function render()
    {
        return view('tlemcen::livewire.tlemcen3')
        ->layout('tlemcen::layouts.app');
    }
}