<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MyApp\PdoGsb;

class connexionController extends Controller
{
    public function connecter()
    {
        return view('connexion')->with('erreurs', null);
    }

    public function valider(Request $request)
    {
        $login = $request->input('login');
        $mdp = $request->input('mdp');

        $pdo = new PdoGsb();
        $admin = PdoGsb::getInfosCompta($login, $mdp);
        $visiteur = $pdo->getInfosVisiteur($login, $mdp);

        if (!$admin && !$visiteur) {
            $erreurs = ['Login ou mot de passe incorrect(s)'];
            return view('connexion')->with('erreurs', $erreurs);
        }

        if ($admin) {
            session(['comptable' => $admin]);
            return view('sommaireCompta')->with('comptable', $admin);
        }

        if ($visiteur) {
            session(['visiteur' => $visiteur]);
            return view('sommaire')->with('visiteur', $visiteur);
        }
    }

    public function deconnecter()
    {
        session()->forget(['visiteur', 'comptable']);
        return redirect()->route('chemin_connexion');
    }
}
