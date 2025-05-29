<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PdoGsb;

class adminController extends Controller
{
    // pour une année tous les frais par visiteur
    function voirFraisParVisiteurPourUneAnne()
    {
        if (session('comptable') != null) {
            $comptable = session("comptable");

            $lesAnnees = PdoGsb::getLesAnnees();

            $vue = view('voirFraisParVisiteurPourUneAnne')
                ->with('comptable', $comptable)
                ->with('lesAnnees', $lesAnnees);

            return $vue;
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }

    function afficherFraisParVisiteurPourUneAnne(Request $request)
    {
        if (session('comptable') != null) {
            $comptable = session("comptable");

            $lAnnee = $request['annee'];
            $lesAnnees = PdoGsb::getLesAnnees();

            $lesInfos = PdoGsb::getInfosParAnnee($lAnnee);
            $json = json_encode($lesInfos);
            file_put_contents("fraisParVisiteur.json",$json);

            $vue = view('afficherFraisParVisiteurPourUneAnne')
                ->with('comptable', $comptable)
                ->with('lesInfos', $lesInfos)
                ->with('lesAnnees', $lesAnnees)
                ->with('lAnnee', $lAnnee);

            return $vue;
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }


    // pour un visiteur tous les frais par mois
    function voirFraisParMoisPourUnVisiteur()
    {
        if (session('comptable') != null) {
            $comptable = session("comptable");

            $lesVisiteurs = PdoGsb::getLesVisiteurs();

            $vue = view('voirFraisParMoisPourUnVisiteur')
                ->with('comptable', $comptable)
                ->with('lesVisiteurs', $lesVisiteurs);

            return $vue;
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }

    function afficherFraisParMoisPourUnVisiteur(Request $request)
    {
        if (session('comptable') != null) {
            $comptable = session("comptable");

            $leVisiteur = $request['visiteur'];
            $lesVisiteurs = PdoGsb::getLesVisiteurs();

            $lesInfos = PdoGsb::getInfosParVisiteur($leVisiteur);
            $json = json_encode($lesInfos);
            file_put_contents("fraisParMois.json",$json);

            $vue = view('afficherFraisParMoisPourUnVisiteur')
                ->with('comptable', $comptable)
                ->with('lesInfos', $lesInfos)
                ->with('lesVisiteurs', $lesVisiteurs)
                ->with('leVisiteur', $leVisiteur);

            return $vue;
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }


    // pour un type de frais tous les montants par mois et par visiteur
    function voirFraisParMoisEtParVisiteurPourUnTypeDeFrais()
    {
        if (session('comptable') != null) {
            $comptable = session("comptable");

            $lesTypes = PdoGsb::getLesTypes();

            $vue = view('voirFraisParMoisEtParVisiteurPourUnTypeDeFrais')
                ->with('comptable', $comptable)
                ->with('lesTypes', $lesTypes);

            return $vue;
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }

    function afficherFraisParMoisEtParVisiteurPourUnTypeDeFrais(Request $request)
    {
        if (session('comptable') != null) {
            $comptable = session("comptable");

            $leType = $request['type'];
            $lesTypes = PdoGsb::getLesTypes();

            $lesInfos = PdoGsb::getLesInfosParType($leType);
            $json = json_encode($lesInfos);
            file_put_contents("fraisParMoisEtParVisiteur.json",$json);

            $vue = view('afficherFraisParMoisEtParVisiteurPourUnTypeDeFrais')
                ->with('comptable', $comptable)
                ->with('lesInfos', $lesInfos)
                ->with('leType', $leType)
                ->with('lesTypes', $lesTypes);

            return $vue;
        } else {
            return view('connexion')->with('erreurs', null);
        }
    }


}
