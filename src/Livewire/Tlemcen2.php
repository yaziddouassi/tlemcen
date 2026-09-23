<?php

namespace Tlemcen\Tlemcen\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Eloquent\Collection;

use Carbon\Carbon;

use App\Models\User;

use Tlemcen\Tlemcen\Utils\Rdvous;
use Tlemcen\Tlemcen\Models\RendezvousHoraire;
use Tlemcen\Tlemcen\Models\RendezvousJouractif;

class Tlemcen2 extends Component
{
    use WithPagination;

    public $jour;
    public $mois;
    public $annee;
    public $journee;

    public $madate;
    public $ladate;
    public $currentdate;

    public $formattedDate;

    public $jouractif;

    public $heureDebut = '';
    public $heureFin = '';
    public $heureId = '';

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

    public $lesheures;


    /*
    |--------------------------------------------------------------------------
    | MOUNT
    |--------------------------------------------------------------------------
    */

    public function mount($madate)
    {
        $rdvous = new Rdvous();

        $rdvous->initier($madate);

        $this->currentdate = $madate;

        $this->jour = $rdvous->getJour();
        $this->mois = $rdvous->getMois();
        $this->annee = $rdvous->getAnnee();
        $this->journee = $rdvous->getJournee();

        $this->madate = $madate;

        /*
        |--------------------------------------------------------------------------
        | Date Carbon
        |--------------------------------------------------------------------------
        */

        $this->ladate = Carbon::create(
            $this->annee,
            $this->mois,
            $this->jour,
            23,
            59,
            59,
            'Europe/Paris'
        );

        /*
        |--------------------------------------------------------------------------
        | Date affichée en français
        |--------------------------------------------------------------------------
        */

        $this->formattedDate = $this->ladate
            ->locale('fr')
            ->translatedFormat('j F Y');

        $this->initier2('non');

        $this->initier();
    }


    /*
    |--------------------------------------------------------------------------
    | AJOUTER DES HORAIRES
    |--------------------------------------------------------------------------
    */

    public function addHoraires($a)
    {
        foreach ($this->tabHoraires as $key => $heureprise) {

            if (
                empty($heureprise['debut']) ||
                empty($heureprise['fin'])
            ) {
                continue;
            }

            $heure = RendezvousHoraire::where('annee', $this->annee)
                ->where('mois', $this->mois)
                ->where('jour', $this->jour)
                ->where('debut', $heureprise['debut'])
                ->first();

            if (!$heure) {

                RendezvousHoraire::create([
                    'annee' => $this->annee,
                    'mois' => $this->mois,
                    'jour' => $this->jour,
                    'debut' => $heureprise['debut'],
                    'fin' => $heureprise['fin'],
                    'journee' => $this->journee,
                    'ladate' => $this->ladate,

                    'userid' => 0,
                    'usernom' => null,
                    'usermail' => null,
                    'useradresse' => null,
                ]);
            }
        }

        $this->initier2($a);
        $this->initier();

        $this->jouractif = RendezvousJouractif::where('annee', $this->annee)
            ->where('mois', $this->mois)
            ->where('jour', $this->jour)
            ->first();

        if ($this->jouractif) {

            if ($a == 'oui') {
                $this->jouractif->status = 'oui';
                $this->jouractif->save();
            }

            if ($a == 'non') {
                $this->jouractif->status = 'non';
                $this->jouractif->save();
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


    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER UN RENDEZ-VOUS
    |--------------------------------------------------------------------------
    */

    public function supprimerRdv($a)
    {
        $heureajoute = RendezvousHoraire::find($a);

        if ($heureajoute) {

            $heureajoute->userid = 0;
            $heureajoute->usernom = null;
            $heureajoute->usermail = null;
            $heureajoute->userprenom = null;
            $heureajoute->usertelephone = null;
            $heureajoute->useradresse = null;

            $heureajoute->save();

            $this->js("
                Swal.fire({
                    title: 'Bravo!',
                    text: 'le rendez-vous a été supprimée',
                    icon: 'success',
                    confirmButtonText: 'valider'
                })
            ");
        }

        $this->initier();
        $this->initier2('non');
    }


    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER UNE HEURE
    |--------------------------------------------------------------------------
    */

    public function supprimerHeure($a)
    {
        $heureajoute = RendezvousHoraire::find($a);

        if ($heureajoute) {

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


    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER UNE HEURE 2
    |--------------------------------------------------------------------------
    */

    public function supprimerHeure2($a)
    {
        $heureajoute = RendezvousHoraire::find($a);

        if ($heureajoute) {

            if ($heureajoute->userid == 0) {

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

            if ($heureajoute->userid != 0) {

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


    /*
    |--------------------------------------------------------------------------
    | MODIFIER RENDEZ-VOUS
    |--------------------------------------------------------------------------
    */

    public function modifierRdv(
        $userid,
        $username,
        $userprenom,
        $usermail,
        $usertelephone,
        $useradresse
    ) {
        $heureajoute = RendezvousHoraire::find($this->heureId);

        if ($heureajoute) {

            $heureajoute->userid = $userid;
            $heureajoute->usernom = $username;
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


    /*
    |--------------------------------------------------------------------------
    | AJOUTER RENDEZ-VOUS
    |--------------------------------------------------------------------------
    */

    public function ajouterRdv(
        $userid,
        $username,
        $userprenom,
        $usermail,
        $usertelephone,
        $useradresse
    ) {
        $heureajoute = RendezvousHoraire::find($this->heureId);

        if ($heureajoute) {

            if ($heureajoute->userid == 0) {

                $heureajoute->userid = $userid;
                $heureajoute->usernom = $username;
                $heureajoute->usermail = $usermail;
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

            elseif ($heureajoute->userid != 0) {

                $this->js("
                    Swal.fire({
                        title: 'Attention!',
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


    /*
    |--------------------------------------------------------------------------
    | CHARGER LES HORAIRES
    |--------------------------------------------------------------------------
    */

    public function initier()
    {
        $this->lesheures = RendezvousHoraire::where(
            'annee',
            $this->annee
        )
            ->where('mois', $this->mois)
            ->where('jour', $this->jour)
            ->orderBy('debut')
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | INITIALISATION JOUR
    |--------------------------------------------------------------------------
    */

    public function initier2($a)
    {
        $count1 = RendezvousHoraire::where('annee', $this->annee)
            ->where('mois', $this->mois)
            ->where('jour', $this->jour)
            ->where('userid', 0)
            ->count();

        $count2 = RendezvousHoraire::where('annee', $this->annee)
            ->where('mois', $this->mois)
            ->where('jour', $this->jour)
            ->where('userid', '!=', 0)
            ->count();

        $jouractif = RendezvousJouractif::where('annee', $this->annee)
            ->where('mois', $this->mois)
            ->where('jour', $this->jour)
            ->first();

        if (!$jouractif) {

            RendezvousJouractif::create([
                'annee' => $this->annee,
                'mois' => $this->mois,
                'jour' => $this->jour,
                'journee' => $this->journee,
                'ladate' => $this->ladate,
                'nbheuredispo' => $count1,
                'nbheureserve' => $count2,
                'status' => $a,
            ]);
        }

        else {

            RendezvousJouractif::find($jouractif->id)->update([
                'nbheuredispo' => $count1,
                'nbheureserve' => $count2,
            ]);
        }

        $this->jouractif = RendezvousJouractif::where(
            'annee',
            $this->annee
        )
            ->where('mois', $this->mois)
            ->where('jour', $this->jour)
            ->first();
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVER
    |--------------------------------------------------------------------------
    */

    public function activer()
    {
        $this->jouractif->status = 'oui';

        $this->jouractif->save();
    }


    /*
    |--------------------------------------------------------------------------
    | DESACTIVER
    |--------------------------------------------------------------------------
    */

    public function desactiver()
    {
        $this->jouractif->status = 'non';

        $this->jouractif->save();
    }


    /*
    |--------------------------------------------------------------------------
    | RECHERCHE
    |--------------------------------------------------------------------------
    */

    public function annulerSearch()
    {
        $this->search = '';
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout()
    {
        Auth::logout();

        request()
            ->session()
            ->invalidate();

        request()
            ->session()
            ->regenerateToken();

        return redirect('/');
    }


    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        if ($this->search == '') {

            $users = \Tlemcen\Tlemcen\Models\RendezvousClient::paginate(4);
        }

        else {

            $users = \Tlemcen\Tlemcen\Models\RendezvousClient::where(
                'usernom',
                'like',
                '%' . $this->search . '%'
            )
                ->orWhere(
                    'userprenom',
                    'like',
                    '%' . $this->search . '%'
                )
                ->paginate(4);
        }

        return view(
            'tlemcen::livewire.tlemcen2',
            [
                'users' => $users,
            ]
        )
            ->layout('tlemcen::layouts.app');
    }
}