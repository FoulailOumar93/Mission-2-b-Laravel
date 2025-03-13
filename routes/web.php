<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
        /*-------------------- Use case connexion---------------------------*/
Route::get('/',[
        'as' => 'chemin_connexion',
        'uses' => 'connexionController@connecter'
]);

Route::post('/',[
        'as'=>'chemin_valider',
        'uses'=>'connexionController@valider'
]);
Route::get('deconnexion',[
        'as'=>'chemin_deconnexion',
        'uses'=>'connexionController@deconnecter'
]);

         /*-------------------- Use case état des frais---------------------------*/
Route::get('selectionMois',[
        'as'=>'chemin_selectionMois',
        'uses'=>'etatFraisController@selectionnerMois'
]);

Route::post('listeFrais',[
        'as'=>'chemin_listeFrais',
        'uses'=>'etatFraisController@voirFrais'
]);

        /*-------------------- Use case gérer les frais---------------------------*/

Route::get('gererFrais',[
        'as'=>'chemin_gestionFrais',
        'uses'=>'gererFraisController@saisirFrais'
]);

Route::post('sauvegarderFrais',[
        'as'=>'chemin_sauvegardeFrais',
        'uses'=>'gererFraisController@sauvegarderFrais'
]);

        /*-------------------- Mission 2A ---------------------------*/

//2

Route::get('voirVisiteur',[
        'as'=>'chemin_voirVisiteur',
        'uses'=>'gererVisiteurController@voirVisiteur'
]);

//2.1

Route::get('ajouterVisiteur',[
        'as'=>'chemin_ajouterVisiteur',
        'uses'=>'gererVisiteurController@saisirVisiteur'
]);

Route::post('sauvegarderVisiteur',[
        'as'=>'chemin_sauvegarderVisiteur',
        'uses'=>'gererVisiteurController@sauvegarderVisiteur'
]);

//3.1


Route::get('supprimerVisiteur',[
        'as'=>'chemin_supprimerVisiteur',
        'uses'=>'gererVisiteurController@supprimerVisiteur'
]);

Route::get('edit/{id}','gererVisiteurController@edit');

Route::get('edit{id}',[
        'as'=>'chemin_modifier',
        'uses'=>'gererVisiteurController@edit'
]);

Route::post('saveEdit',[
        'as' => 'chemin_saveVisiteur',
        'uses' => 'gererVisiteurController@saveEdit'
]);

Route::get('/generer-pdf-visiteurs', 'gererVisiteurController@genererPDFVisiteurs')->name('generer.pdf.visiteurs');
