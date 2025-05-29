<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MyApp\PdoGsb;

class ConnexionControllerG extends Controller
{
    public function connecter()
    {
        return view('connexionG')->with('erreurs', null);
    }

    public function valider(Request $request)
    {
        $login = $request->input('login');
        $mdp = $request->input('mdp');

        $admin = PdoGsb::getInfosCompta($login, $mdp);

        if (!$admin) {
            $erreurs[] = "Login ou mot de passe incorrect(s)";
            return view('connexionG')->with('erreurs', $erreurs);
        }

        session(['comptable' => $admin]);

        return view('sommaireCompta')->with('comptable', $admin); // ✅ Page d’accueil admin
    }

    public function deconnecter()
    {
        session()->forget('comptable');
        return redirect()->route('connexion_admin');
    }
}
