<?php

namespace App\Http\Controllers;

use App\Facades\PdoGsb as FacadesPdoGsb;
use Illuminate\Http\Request;
use PdoGsb;
use App\Models\Visiteur;  //c'est moi qui l'ai rajouté
use App\MyApp\PdoGsb as MyAppPdoGsb;
use PDF;

class gererVisiteurController extends Controller {
    function voirVisiteur() {
        if (session()->has('visiteur')) {
            $visiteur = session('visiteur');
            $lesVisiteurs = PdoGsb::afficherVisiteurs();
            //$visiteur = session('visiteur');
            return view('listevisiteur')->with('lesVisiteurs', $lesVisiteurs)->with('visiteur',$visiteur);;
        } else {
            return redirect()->route('connexion')->with('erreurs', null);
        }
    }

    function saisirVisiteur(Request $request){
        if( session('visiteur') != null){
            $visiteur = session('visiteur');
            $idVisiteur = $visiteur['id'];
            $view = view('ajouterVisiteur')
                    ->with('visiteur',$visiteur)
                    ->with('message',"")
                    ->with ('method',$request->method());
            return $view;
        }
        else{
            return view('connexion')->with('erreurs',null);
        }
    }

    public function sauvegarderVisiteur(Request $request){
        if( session('visiteur') != null){
            $visiteur = session('visiteur');
            $idVisiteur = $visiteur['id'];              
            $nom = $request['nom'];                            
            $prenom = $request['prenom'];
            $login = PdoGsb::genererLogin($prenom,$nom);
            $adresse = $request['adresse'];
            $cp = $request['cp'];
            $ville = $request['ville'];
            $time = strtotime($request['DE']);
            $newformat = date('Y-m-d',$time);
            $message = "Les informations ont bien été ajoutés.";
            PdoGsb::majVisiteur($nom,$prenom,$login,$adresse,$cp,$ville,$newformat);                      
            $view = view('ajouterVisiteur')
                    ->with('visiteur',$visiteur)
                    ->with('message',$message)
                    ->with ('method',$request->method());
                    $erreurs = null;
            return $view;
        }
        else{
            return view('connexion')->with('erreurs',null);
        }
    }

    public function edit($id)
    {
        $visiteur = session('visiteur');
        if( session('visiteur') != null){
        $user = PdoGsb::afficherLeVisiteur($id); 
        return view('edit')
            ->with('visiteur', $visiteur)
            ->with('user', $user);
        }
    }

    public function saveEdit(Request $request)
    {
        if( session('visiteur') != null){
            $visiteur = session('visiteur');
            $id = $request['id'];
            $nom = $request['nom'];                             
            $prenom = $request['prenom'];
            $login = PdoGsb::genererLogin($prenom,$nom);
            $adresse = $request['adresse'];
            $cp = $request['cp'];
            $ville = $request['ville'];
            $time = strtotime($request['DE']);
            $newformat = date('Y-m-d',$time);
            $message = "Les informations ont bien été ajoutés.";
        }
    }



    /*public function supprimerVisiteur(Request $request)
    {
        $visiteur = session('visiteur');
        $idVisiteur = $visiteur['id'];              
        $nom = $request['nom'];                             
        $prenom = $request['prenom'];
        $login = PdoGsb::genererLogin($prenom,$nom);
        $adresse = $request['adresse'];
        $cp = $request['cp'];
        $ville = $request['ville'];
        $time = strtotime($request['DE']);
        $newformat = date('Y-m-d',$time);
        if( session('visiteur') != null){
            $visiteur = session('visiteur');
            $leVisiteur = PdoGsb::supprimerVisiteurs($nom,$prenom,$login,$adresse,$cp,$ville,$time,$newformat);
            return view('listevisiteur')
                ->with('leVisiteur',$leVisiteur)
                ->with('visiteur',$visiteur)
                ->with('message',"");
        }
    }*/

    public function genererPDFVisiteurs()
    {
        $visiteur = session('visiteur');
        if( session('visiteur') != null) {
        $visiteurs = PdoGsb::afficherVisiteursParDE();
        $pdf = PDF::loadView('listeVisiteurPDF', compact('visiteurs'));
        return $pdf->download('liste_visiteur.pdf');
        }
    }

    /*function genEtat() {
        if (session()->has('visiteur')) {
            $visiteur = session('visiteur');
            $lesVisiteurs = PdoGsb::afficherVisiteurs();
            //$visiteur = session('visiteur');
            return view('listevisiteur')->with('lesVisiteurs', $lesVisiteurs)->with('visiteur',$visiteur);;
        } else {
            return redirect()->route('connexion')->with('erreurs', null);
        }
    }*/
}