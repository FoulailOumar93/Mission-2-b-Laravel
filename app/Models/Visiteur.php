<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visiteur extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'visiteur';

    protected $fillable = [
        'id',
        'nom',
        'prenom',
        'login',
        'mdp',
        'adresse',
        'cp',
        'ville',
        'dateEmbauche',
        // Ajoutez d'autres colonnes si nécessaire
    ];

    // Définissez les relations avec d'autres modèles si nécessaire
}
