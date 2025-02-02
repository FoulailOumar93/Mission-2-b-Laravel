<?php

namespace App\Http\Controllers;

use App\Facades\PdoGsb as FacadesPdoGsb;
use Illuminate\Http\Request;
use PdoGsb;
use MyDate;
use App\Models\Visiteur;  //c'est moi qui l'ai rajouté
use App\MyApp\PdoGsb as MyAppPdoGsb;
use PDF;

class ficheFraisController2B extends Controller {

    //liste déroulante 
    public function selectionnerVisiteur(){
        if (session()->has('visiteur')) {
            $visiteur = session('visiteur');
            $lesVisiteurs = null;
            $lesVisiteurs = PdoGsb::afficherVisiteurs2B();
            $lesMois = PdoGsb::getLesMois();
		    // Afin de sélectionner par défaut le dernier mois dans la zone de liste
		    // on demande toutes les clés, et on prend la première,
		    // les mois étant triés décroissants
		    $lesCles = array_keys( $lesMois );
            $moisASelectionner = $lesCles[0];

            $lesCles2 = array_keys( $lesVisiteurs );
            $visiteurASelectionner = $lesCles2[0];
            return view('selectionFicheFrais2B')
                ->with('visiteur',$visiteur)
                ->with('lesVisiteurs', $lesVisiteurs)
                ->with('lesMois', $lesMois)
                ->with('leVisiteur', $visiteurASelectionner)
                ->with('leMois', $moisASelectionner);
        } else {
            return redirect()->route('connexion')->with('erreurs', null);
        }
    }

    //affichage fiche frais après sélection du visiteur
    public function voirFicheFrais(Request $request) {
        if (session('visiteur') != null) {
            $visiteur = session('visiteur');
            $idVisiteur = $request->input('lstVisiteur');
            $leMois = $request->input('lstMois');
            
            $lesVisiteurs = PdoGsb::afficherVisiteurs2B();
            $lesMois = PdoGsb::getLesMois();
    
            // Utilisez la méthode input() pour récupérer la valeur du formulaire
            $lesInfosFicheFrais = PdoGsb::getLesInfosFicheFrais($idVisiteur, $leMois);
    
            if (!$lesInfosFicheFrais) {                                                                             //Faire comme dans controller 'gererFraisController'
                                                                                                                    // mettre la vue dans une variable $view
                // Affiche un message d'erreur et retourne à la vue précédente
                $erreurs[] = "Il n'y a pas de fiche frais pour le visiteur sélectionné et le mois choisi.";
                return view('msgerreurs')->with('erreurs', $erreurs);
            } else {
                // Continuez avec le reste du code pour afficher la fiche frais
                $numAnnee = MyDate::extraireAnnee($leMois);
                $numMois = MyDate::extraireMois($leMois);
                
                $libEtat = $lesInfosFicheFrais['libEtat'];
                $montantValide = $lesInfosFicheFrais['montantValide'];
                $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
                $dateModif =  $lesInfosFicheFrais['dateModif'];
                $dateModifFr = MyDate::getFormatFrançais($dateModif);
    
                return view('listeFicheFraisDuVisiteur')->with('lesMois', $lesMois)
                    ->with('lesVisiteurs', $lesVisiteurs)
                    ->with('leMois', $leMois)->with('numAnnee', $numAnnee)
                    ->with('numMois', $numMois)->with('libEtat', $libEtat)
                    ->with('montantValide', $montantValide)
                    ->with('nbJustificatifs', $nbJustificatifs)
                    ->with('dateModif', $dateModifFr)
                    ->with('lesFraisForfait', PdoGsb::getLesFraisForfait($idVisiteur, $leMois)) // Ajout des frais forfait ici
                    ->with('visiteur', $visiteur);
            }
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }
    
}