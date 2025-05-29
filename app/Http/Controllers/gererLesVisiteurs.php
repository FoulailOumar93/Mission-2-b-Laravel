<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MyApp\PdoGsb;
use PDF;

class gererLesVisiteurs extends Controller
{
    private function estAdmin()
    {
        return session()->has('comptable');
    }

    public function voirVisiteur(Request $request)
    {
        if (!$this->estAdmin()) {
            return redirect()->route('chemin_connexion')->with('erreurs', ['Accès refusé.']);
        }

        $ordre = $request->query('order', 'asc'); // asc ou desc
        $orderBy = $request->query('orderBy', 'nom'); // colonne de tri

        $pdo = new PdoGsb();
        $lesVisiteurs = $pdo->afficherVisiteurs();

        // Tri dynamique selon la colonne
        usort($lesVisiteurs, function ($a, $b) use ($ordre, $orderBy) {
            $valA = strtolower($a[$orderBy] ?? '');
            $valB = strtolower($b[$orderBy] ?? '');
            return $ordre === 'desc' ? $valB <=> $valA : $valA <=> $valB;
        });

        $comptable = session('comptable');
        return view('listevisiteur', compact('lesVisiteurs', 'comptable', 'ordre', 'orderBy'));
    }

    public function saisirVisiteur()
    {
        if (!$this->estAdmin()) {
            return redirect()->route('chemin_connexion')->with('erreurs', ['Accès refusé.']);
        }

        $comptable = session('comptable');
        return view('ajouterVisiteur', compact('comptable'));
    }

  public function saveVisiteur(Request $request)
{
    if (!$this->estAdmin()) {
        return redirect()->route('chemin_connexion')->with('erreurs', ['Accès refusé.']);
    }

    $validated = $request->validate([
        'nom' => 'required',
        'prenom' => 'required',
        'adresse' => 'required',
        'cp' => 'required',
        'ville' => 'required',
        'DE' => 'required|date',
        'naissance' => 'required|date',
        'login' => 'required',
        'email' => 'nullable|email'
    ]);

    // Génération automatique uniquement si non fourni
    $email = $validated['email'] ?? strtolower($validated['nom'] . '.' . $validated['prenom'] . date('dmY', strtotime($validated['naissance'])) . '@gmail.com');

    $pdo = new PdoGsb();
    $pdo->ajouterVisiteur(
        $validated['nom'],
        $validated['prenom'],
        $validated['adresse'],
        $validated['cp'],
        $validated['ville'],
        $validated['DE'],
        $validated['login'],
        $email
    );

    return redirect()->route('chemin_voirVisiteur')->with('message', 'Visiteur ajouté avec succès.');
}


    public function edit($id)
    {
        if (!$this->estAdmin()) {
            return redirect()->route('chemin_connexion')->with('erreurs', ['Accès refusé.']);
        }

        $pdo = new PdoGsb();
        $user = $pdo->afficherLeVisiteur($id);
        $comptable = session('comptable');

        return view('majVisiteur', compact('user', 'comptable'));
    }

    public function saveEdit(Request $request, $id)
    {
        if (!$this->estAdmin()) {
            return redirect()->route('chemin_connexion')->with('erreurs', ['Accès refusé.']);
        }

        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'login' => 'required',
            'mdp' => 'required',
            'adresse' => 'required',
            'cp' => 'required',
            'ville' => 'required',
            'DE' => 'required|date'
        ]);

        $pdo = new PdoGsb();
        $pdo->modifierVisiteur(
            $id,
            $request->input('nom'),
            $request->input('prenom'),
            $request->input('login'),
            $request->input('mdp'),
            $request->input('adresse'),
            $request->input('cp'),
            $request->input('ville'),
            $request->input('DE')
        );

        return redirect()->route('chemin_voirVisiteur')->with('success', 'Visiteur modifié avec succès.');
    }

    public function confirmerSuppression($id)
    {
        if (!$this->estAdmin()) {
            return redirect()->route('chemin_connexion')->with('erreurs', ['Accès refusé.']);
        }

        $pdo = new PdoGsb();
        $visiteur = $pdo->afficherLeVisiteur($id);

        return view('supprimerVisiteur', compact('visiteur'));
    }

    public function supprimerVisiteur($id)
    {
        if (!$this->estAdmin()) {
            return redirect()->route('chemin_connexion')->with('erreurs', ['Accès refusé.']);
        }

        $pdo = new PdoGsb();
        $pdo->supprimerFichesFraisVisiteur($id);
        $pdo->getSupprimerVisiteur($id);

        return redirect()->route('chemin_voirVisiteur')->with('message', 'Visiteur supprimé avec succès.');
    }

public function genererPDFVisiteurs(Request $request)
{
    if (!$this->estAdmin()) {
        return redirect()->route('chemin_connexion')->with('erreurs', ['Accès refusé.']);
    }

    $ordre = $request->query('order', 'asc'); // asc ou desc
    $orderBy = $request->query('orderBy', 'nom'); // colonne choisie

    $pdo = new PdoGsb();
    $lesVisiteurs = $pdo->afficherVisiteurs();

    // Tri dynamique selon la colonne demandée
    usort($lesVisiteurs, function ($a, $b) use ($ordre, $orderBy) {
        $valA = strtolower($a[$orderBy] ?? '');
        $valB = strtolower($b[$orderBy] ?? '');
        return $ordre === 'desc' ? $valB <=> $valA : $valA <=> $valB;
    });

    $pdf = PDF::loadView('listeVisiteurPDF', compact('lesVisiteurs', 'ordre', 'orderBy'));
    return $pdf->download('liste_visiteurs.pdf');
}
}