<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use PdoGsb;

class connexionControllerG extends Controller
{
    // function connecterG(){

    //     return view('connexionG')->with('erreurs',null);
    // }
    // function validerG(Request $request){
    //     $login = $request['login'];
    //     $mdp = $request['mdp'];
    //     $gestionnaire = PdoGsb::getInfosGestionnaire($login,$mdp);
    //     if(!is_array($gestionnaire)){
    //         $erreurs[] = "Login ou mot de passe incorrect(s)";
    //         return view('connexionG')->with('erreurs',$erreurs);
    //     }
    //     else{
    //         session(['gestionnaire' => $gestionnaire]);
    //         return view('sommaireG')->with('gestionnaire',session('gestionnaire'));
    //     }
    // }
    // function deconnecterG(){
    //         session(['gestionnaire' => null]);
    //         return redirect()->route('chemin_connexionG');


    // }
    public function connecterG()
    {
        return view('connexionG')->with('erreurs', null);
    }

    public function validerG(Request $request)
    {
        $login = $request->input('login');
        $mdp = $request->input('mdp');

        $visiteur = PdoGsb::getInfosVisiteur($login, $mdp);
        $gestionnaire = PdoGsb::getInfosGestionnaire($login, $mdp);

        if (!$visiteur && !$gestionnaire) {
            $erreurs[] = "Login ou mot de passe incorrect(s)";
            return view('connexionG')->with('erreurs', $erreurs);
        }

        if ($visiteur) {
            session(['visiteur' => $visiteur]);
            return view('sommaireG')->with('visiteur', $visiteur);
        } elseif ($gestionnaire) {
            session(['gestionnaire' => $gestionnaire]);
            return view('sommaireG')->with('gestionnaire', $gestionnaire);
        }
    }

    public function deconnecterG()
    {
        session(['visiteur' => null, 'gestionnaire' => null]);
        return redirect()->route('chemin_connexionG');
    }
}
