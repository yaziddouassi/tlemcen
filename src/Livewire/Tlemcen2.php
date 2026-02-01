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
use Tlemcen\Tlemcen\Models\RendezvousJouractif;

class Tlemcen2 extends Component
{ 
    use WithPagination;
    public $jour ;
    public $mois ;
    public $annee ;
    public $journee ;
    public $madate ;
    public $ladate ;
    public $currentdate;
    public $heureDebut = '' ;
    public $heureFin = '' ;
    public $heureId = '' ;
    public $open1 = false;
    public $open2 = false;
    public $open3 = false;
    public $search = '';
    public array $tabHoraires = [
        ['debut' => '', 'fin' => ''],
        ['debut' => '', 'fin' => ''],
        ['debut' => '', 'fin' => ''],
        ['debut' => '', 'fin' => ''],
    ];

    public $lesheures ;

    
      public function logout()
{
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
}

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

          $this->initier2('non');
          $this->initier() ;

       
    
    }

    public function addHoraires($a) {
        
        


          foreach($this->tabHoraires as $key=>$heureprise) {

         
      $heure =  RendezvousHoraire::where('annee',$this->annee)
        ->where('mois',$this->mois)
         ->where('jour',$this->jour)
         ->where('debut',$heureprise['debut'])
        ->first() ;


        if(!$heure) {

          

            RendezvousHoraire::create([
                    'annee' => $this->annee ,
                    'mois' => $this->mois ,
                    'jour' => $this->jour ,
                    'debut' => $heureprise['debut'] ,
                    'fin' => $heureprise['fin'] ,
                    'journee' => $this->journee,
                    'ladate' => $this->ladate ,
                    'userid' => 0 ,
                    'usernom' => null ,
                    'usermail' => null,
                    'useradresse' => null,
            ]);
        }




    }

    
    $this->initier2($a);
    $this->initier() ;

    $jouractif = RendezvousJouractif::where('annee',$this->annee)
                          ->where('mois',$this->mois)
                           ->where('jour',$this->jour)
                           ->first() ;

    if( $jouractif) {
       if($a == 'oui') {
          $jouractif->status = 'oui';
          $jouractif->save();
       }
       if($a == 'non') {
          $jouractif->status = 'non';
          $jouractif->save();
       }
    }

    $this->open1 = false;
    
    $this->tabHoraires = [
        ['debut' => '', 'fin' => ''],
        ['debut' => '', 'fin' => ''],
        ['debut' => '', 'fin' => ''],
        ['debut' => '', 'fin' => ''],
    ];

    }


    public function supprimerRdv($a) {

     $heureajoute = RendezvousHoraire::find($a);

     if($heureajoute) {

        $heureajoute->userid = 0 ;
        $heureajoute->usernom = null ;
        $heureajoute->usermail = null ;
        $heureajoute->userprenom = null;
        $heureajoute->usertelephone = null;
        $heureajoute->useradresse = null;
        $heureajoute->save();



     }


     $this->js("
               Swal.fire({
                 title: 'Bravo!',
                 text: 'le rendez-vous a été supprimée',
                 icon: 'success',
                 confirmButtonText: 'valider'
                               })
                           ");

    
      $this->initier();
      $this->initier2('non');


  }


  public function supprimerHeure($a) {

     $heureajoute = RendezvousHoraire::find($a);

     if($heureajoute) {

        RendezvousHoraire::destroy($a);
        $this->js("
               Swal.fire({
                 title: 'Bravo!',
                 text: 'l heure a bien été supprimé',
                 icon: 'success',
                 confirmButtonText: 'valider'
                               })
                           ");

     }

    
      $this->initier();
      $this->initier2('non');


  }




  public function supprimerHeure2($a) {

     $heureajoute = RendezvousHoraire::find($a);

     if($heureajoute) {

       if($heureajoute->userid == 0) {
        RendezvousHoraire::destroy($a);
        $this->js("
               Swal.fire({
                 title: 'Bravo!',
                 text: 'l heure a bien été supprimé',
                 icon: 'success',
                 confirmButtonText: 'valider'
                               })
                           ");
       }


       if($heureajoute->userid != 0) {
        $this->js("
               Swal.fire({
                 title: 'Attention!',
                 text: 'il y a déja un rendez-vous',
                 icon: 'error',
                 confirmButtonText: 'valider'
                               })
                           ");
       }



     }

    
      $this->initier();
      $this->initier2('non');


  }

   public function modifierRdv($userid,$username,$userprenom,
   $usermail,$usertelephone,$useradresse) {

    $heureajoute = RendezvousHoraire::find($this->heureId);

    if($heureajoute) {
        $heureajoute->userid = $userid ;
        $heureajoute->usernom =  $username;
        $heureajoute->usermail = $usermail;
        $heureajoute->userprenom = $userprenom;
        $heureajoute->usertelephone = $usertelephone;
        $heureajoute->useradresse = $useradresse;
        $heureajoute->save();

        $this->js("
               Swal.fire({
                 title: 'Bravo!',
                 text: 'le rendez vous a ete bien modifié',
                 icon: 'success',
                 confirmButtonText: 'valider'
                               })
                           ");
    }

    $this->initier();
    $this->initier2('non');
    $this->open3 = false;
   }

   
 public function ajouterRdv($userid,$username,$userprenom,
   $usermail,$usertelephone,$useradresse) {
     
 
   $heureajoute = RendezvousHoraire::find($this->heureId);

   

   if($heureajoute) {

       if($heureajoute->userid == 0) {
         
          $heureajoute->userid = $userid ;
          $heureajoute->usernom =  $username;
          $heureajoute->usermail =  $usermail;
          $heureajoute->userprenom = $userprenom;
          $heureajoute->usertelephone = $usertelephone;
          $heureajoute->useradresse = $useradresse;
         
          $heureajoute->save();
          $this->js("
               Swal.fire({
                 title: 'Bravo!',
                 text: 'le rendez vous a bien ete pris',
                 icon: 'success',
                 confirmButtonText: 'valider'
                               })
                           ");

       }

       elseif($heureajoute->userid != 0) {
         
          $this->js("
               Swal.fire({
                 title: 'Attentio!',
                 text: 'le rendez vous a déja ete pris',
                 icon: 'error',
                 confirmButtonText: 'valider'
                               })
                           ");

       }


     }

    $this->initier();
    $this->initier2('non');
    $this->open2 = false;

   }


    public function initier() {

      $this->lesheures = RendezvousHoraire::where('annee',$this->annee)
                                 ->where('mois',$this->mois)
                                  ->where('jour',$this->jour)
                                 ->orderBy('debut')
                                 ->get() ;
      
   }


   public function initier2($a) {

        
    $count1 =  RendezvousHoraire::where('annee',$this->annee)
                    ->where('mois',$this->mois)
                    ->where('jour',$this->jour)
                    ->where('userid',0)
                    ->count() ;

    $count2 =  RendezvousHoraire::where('annee',$this->annee)
                    ->where('mois',$this->mois)
                    ->where('jour',$this->jour)
                    ->where('userid','!=',0)
                    ->count() ;

    $jouractif = RendezvousJouractif::where('annee',$this->annee)
                          ->where('mois',$this->mois)
                           ->where('jour',$this->jour)
                           ->first() ;

   if(!$jouractif) {

        RendezvousJouractif::create([
            'annee' => $this->annee ,
            'mois' => $this->mois ,
            'jour' => $this->jour ,
            'journee' => $this->journee ,
            'ladate' => $this->ladate ,
            'nbheuredispo' => $count1 ,
            'nbheureserve' => $count2,
            'status' => $a,
        ]) ;  

   }

   else {

    RendezvousJouractif::find($jouractif->id)->update([
                         'nbheuredispo' => $count1 ,
                         'nbheureserve' => $count2
              ]);
       
   }

 }

    public function annulerSearch() {
        $this->search = '';
    }

    public function render()
    {

        if($this->search == '') {
           $users = \App\Models\User::paginate(4);
        }

        if($this->search != '') {
           $users = \App\Models\User::where('name', 'like', '%'.$this->search.'%')
                                      ->orWhere('prenom', 'like', '%'.$this->search.'%')
                                     ->paginate(4);
        }

        return view('tlemcen::livewire.tlemcen2',
                    ['users' => $users])
        ->layout('tlemcen::layouts.app');
    }
}