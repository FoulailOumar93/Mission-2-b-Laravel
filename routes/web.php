<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConnexionControllerG;
use App\Http\Controllers\connexionController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\gererLesVisiteurs;
use App\Http\Controllers\etatFraisController;
use App\Http\Controllers\gererFraisController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes - Projet GSB
|--------------------------------------------------------------------------
*/

// Connexion visiteur
Route::get('/', [connexionController::class, 'connecter'])->name('chemin_connexion');
Route::post('/', [connexionController::class, 'valider'])->name('chemin_valider');
Route::get('/deconnexion', [connexionController::class, 'deconnecter'])->name('chemin_deconnexion');

// Connexion admin (comptable)
Route::get('/admin', [ConnexionControllerG::class, 'connecter'])->name('connexion_admin');
Route::post('/admin', [ConnexionControllerG::class, 'valider'])->name('valider_admin');
Route::get('/admin/deconnexion', [ConnexionControllerG::class, 'deconnecter'])->name('chemin_deconnexionG');

// Mot de passe oublié (Visiteur)
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'checkLogin'])->name('password.email');
Route::get('/reset-password/{login}', [ForgotPasswordController::class, 'showResetPasswordPage'])->name('password.reset.page');
Route::post('/reset-password', [ForgotPasswordController::class, 'updatePassword'])->name('password.update');

// Mission 1 : État des frais (visiteur)
Route::get('/selectionMois', [etatFraisController::class, 'selectionnerMois'])->name('chemin_selectionMois');
Route::post('/listeFrais', [etatFraisController::class, 'voirFrais'])->name('chemin_listeFrais');

// Mission 1 : Saisie des frais (visiteur)
Route::get('/gererFrais', [gererFraisController::class, 'saisirFrais'])->name('chemin_gestionFrais');
Route::post('/sauvegarderFrais', [gererFraisController::class, 'sauvegarderFrais'])->name('chemin_sauvegardeFrais');

// Mission 2A : Gérer les visiteurs (comptable)
Route::get('/voirVisiteur', [gererLesVisiteurs::class, 'voirVisiteur'])->name('chemin_voirVisiteur');
Route::get('/ajouterVisiteur', [gererLesVisiteurs::class, 'saisirVisiteur'])->name('chemin_ajouterVisiteur');
Route::post('/sauvegarderVisiteur', [gererLesVisiteurs::class, 'saveVisiteur'])->name('chemin_sauvegarderVisiteur');

Route::get('/edit/{id}', [gererLesVisiteurs::class, 'edit'])->name('chemin_modifier');
Route::post('/saveEdit/{id}', [gererLesVisiteurs::class, 'saveEdit'])->name('chemin_saveVisiteur');
Route::get('/supprimerVisiteur/{id}', [gererLesVisiteurs::class, 'confirmerSuppression'])->name('chemin_supprimer');
Route::delete('/supprimerVisiteur/{id}', [gererLesVisiteurs::class, 'supprimerVisiteur'])->name('chemin_supprimerVisiteur');
Route::get('/generer-pdf-visiteurs', [gererLesVisiteurs::class, 'genererPDFVisiteurs'])->name('generer.pdf.visiteurs');

// Mission 2B : Statistiques admin
Route::get('/admin/frais-par-visiteur', [AdminController::class, 'voirFraisParVisiteurAnnee'])->name('chemin_voirFraisParVisiteurAnnee');
Route::get('/admin/frais-par-mois-visiteur', [AdminController::class, 'voirFraisParMoisVisiteur'])->name('chemin_voirFraisParMoisVisiteur');
Route::get('/admin/frais-par-type', [AdminController::class, 'voirFraisParType'])->name('chemin_voirFraisParType');

// 🎨 Thème clair / sombre avec redirection intelligente
Route::post('/theme/toggle', function () {
    $current = session('theme', 'light');
    $newTheme = $current === 'dark' ? 'light' : 'dark';
    session(['theme' => $newTheme]);

    $lastPage = session('last_page', url('/'));
    return redirect($lastPage);
})->name('toggle.theme');
