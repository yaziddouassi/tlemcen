<?php

namespace Tlemcen\Tlemcen\Utils;
use Carbon\Carbon ;

class Rdvous {
 
    public $jour ;
    public $mois ;
    public $annee ;
    public $journee ;

    
    public function getJour() {
        return $this->jour;
    }

    public function getMois() {
        return $this->mois;
    }

    public function getAnnee() {
        return $this->annee;
    }

    public function getJournee() {
        return $this->journee;
    }


    public function initier($madate) {

        
        $date = explode("-",$madate);

        $count = count($date) ;

        if($count != 3) {

            return redirect('/') ;
        }

        $jour = $date[2] ;
        $mois = $date[1] ;
        $annee = $date[0] ;

        

        if (!is_numeric($jour) or !is_numeric($mois) or !is_numeric($annee)) {
            return redirect('/') ;
        }

        $verife =  checkdate ( $mois, $jour, $annee );

      
        if($verife == false) {
            return redirect('/') ;
        }

        if($jour[0] == 0) {
            return redirect('/') ;
        }

        if($mois[0] == 0) {
            return redirect('/') ;
        }


        $now =  Carbon::create($annee,$mois ,$jour,23,59,59, 'Europe/Paris' );

        $day = $now->dayOfWeek ;

        $weekMap = [
            0 => 'Dimanche',
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
        ];

        $weekday = $weekMap[$day];


        $month = $now->month ;

        $monthMap = [

            1 => 'janvier',
            2 => 'février',
            3 => 'mars',
            4 => 'avril',
            5 => 'mai',
            6 => 'juin',
            7 => 'juillet',
            8 => 'aout',
            9 => 'septembre',
            10 => 'octobre',
            11 => 'novembre',
            12 => 'décembre',
        ] ;

        $monthday =  $monthMap[$month];

        $journee = $weekday .' '. $jour .' '. $monthday . ' ' . $annee ;

       $this->jour = $jour ;
       $this->mois = $mois ;
       $this->annee = $annee ;
       $this->journee = $journee ;
        
    }


}
?>