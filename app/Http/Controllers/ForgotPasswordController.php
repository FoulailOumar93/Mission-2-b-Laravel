<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MyApp\PdoGsb;

class ForgotPasswordController extends Controller
{
    private $pdoGsb;

    public function __construct()
    {
        $this->pdoGsb = new PdoGsb();
    }

    // Affiche la page "Mot de passe oublié"
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    // Vérifie le login et redirige vers la page de réinitialisation
    public function checkLogin(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
        ]);

        $login = $request->input('login');
        $user = $this->pdoGsb->getInfosVisiteurParLogin($login);

        if (!$user) {
            return back()->withErrors(['login' => 'Aucun utilisateur trouvé avec ce login.']);
        }

        return redirect()->route('password.reset.page', ['login' => $login])
                         ->with('success', 'Un lien de réinitialisation a été généré.');
    }

    // Affiche la page de réinitialisation du mot de passe
    public function showResetPasswordPage($login)
    {
        $user = $this->pdoGsb->getInfosVisiteurParLogin($login);

        if (!$user) {
            return redirect()->route('chemin_connexion')->withErrors(['login' => 'Login introuvable.']);
        }

        $oldPassword = $user['mdp']; // Supposons que le champ du mot de passe est 'mdp'

        return view('auth.reset-password', [
            'login' => $login,
            'oldPassword' => $oldPassword
        ]);
    }

    // Met à jour le mot de passe (SANS HACHAGE)
    public function updatePassword(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',       // Une majuscule
                'regex:/[a-z]/',       // Une minuscule
                'regex:/[0-9]/',       // Un chiffre
                'regex:/[@$!%*?&]/',   // Un caractère spécial
                'confirmed'            // Vérifie que les deux champs sont identiques
            ],
        ]);

        $login = $request->input('login');
        $newPassword = $request->input('new_password');

        // Vérifie si le mot de passe est identique à l'ancien
        $user = $this->pdoGsb->getInfosVisiteurParLogin($login);

        if (!$user) {
            return back()->withErrors(['login' => 'Login introuvable.']);
        }

        // 🔥 Vérification que le nouveau mot de passe n'est pas identique à l'ancien
        if (trim($user['mdp']) === trim($newPassword)) {
            return back()->withErrors(['new_password' => 'Le nouveau mot de passe ne peut pas être identique à l’ancien.']);
        }

        // Mise à jour du mot de passe
        $this->pdoGsb->updatePassword($login, $newPassword);

        return redirect()->route('chemin_connexion')->with('success', 'Votre mot de passe a été modifié avec succès.');
    }
}
