<?php

namespace Tlemcen\Tlemcen\Livewire;

use Livewire\Component;
use Tlemcen\Tlemcen\Models\RendezvousHoraire ;
use Tlemcen\Tlemcen\Models\RendezvousJouractif;
use App\Models\User ;
use Carbon\Carbon ;
use Auth ;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Petitrdv extends Component
{

    public $ladate ;
    public $journee ;
    public $lesheures ;
    public $annee ;
    public $mois ;
    public $jour ;

    public function mount() {
        $this->charger() ;
    }


    public function valider($a)
{
    $date = Carbon::yesterday();
    $yesterday = Carbon::create($date->year, $date->month, $date->day, 23, 59, 59, 'Europe/Paris');

    $userId = Auth::id();

    DB::beginTransaction();

    try {
        // Check if the user already has a future rendezvous
        $count = RendezvousHoraire::where('userid', $userId)
            ->where('ladate', '>', $yesterday)
            ->count();

        if ($count > 0) {
            DB::rollBack();
            $this->js("
                Swal.fire({
                    title: 'Attention!',
                    text: 'Vous avez déjà un rendez-vous',
                    icon: 'error',
                    confirmButtonText: 'valider'
                })
            ");
            $this->charger();
            return;
        }

        // Retrieve the rendezvous slot using lockForUpdate
        $horaire = RendezvousHoraire::where('id', $a)
            ->where('userid', 0)
            ->lockForUpdate()
            ->first();

        if ($horaire) {
            // Assign the rendezvous to the current user
            $horaire->userid = $userId;
            $horaire->usernom = Auth::user()->name;
            $horaire->usermail = Auth::user()->email;
            $horaire->userprenom = Auth::user()->prenom;
            $horaire->usertelephone = Auth::user()->telephone;
            $horaire->useradresse = Auth::user()->adresse;
            $horaire->save();

            DB::commit(); // Commit transaction

            $this->js("
                Swal.fire({
                    title: 'Bravo!',
                    text: 'Le rendez-vous a été pris',
                    icon: 'success',
                    confirmButtonText: 'valider'
                })
            ");
            
            $this->initier();
            $this->charger();
        } else {
            DB::rollBack();
            $this->js("
                Swal.fire({
                    title: 'Attention!',
                    text: 'Le rendez-vous n'est plus disponible!',
                    icon: 'error',
                    confirmButtonText: 'valider'
                })
            ");
            $this->charger();
        }
    } catch (\Exception $e) {
        DB::rollBack();
        $this->js("
            Swal.fire({
                title: 'Erreur!',
                text: 'Une erreur est survenue. Veuillez réessayer!',
                icon: 'error',
                confirmButtonText: 'valider'
            })
        ");
    }
}



   







    public function initier() {

        $count1 =  RendezvousHoraire::where('ladate',$this->ladate)
                          ->where('userid',0)
                          ->count() ;

        $count2 =  RendezvousHoraire::where('ladate',$this->ladate)
                          ->where('userid','!=',0)
                          ->count() ;

        $jouractif = RendezvousJouractif::where('ladate',$this->ladate)
                  ->first() ;


        if($jouractif) {

            RendezvousJouractif::find($jouractif->id)->update([
                        'nbheuredispo' => $count1 ,
                         'nbheureserve' => $count2
                        ]);
        
               }
      

    }

    public function charger()
    {
        $this->lesheures = RendezvousHoraire::where('ladate',$this->ladate)
                                    ->where('userid',0)
                                    ->orderBy('debut')
                                  ->get();
    }

    public function render()
    {
        return view('tlemcen::livewire.petitrdv');
    }
}
