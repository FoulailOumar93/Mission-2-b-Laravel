@extends('modeles.admin')

@section('menu')
<div id="menuGauche">
    <div id="infosUtil">
        @if(isset($comptable))
            <strong>Bonjour {{ ucfirst($comptable['nom']) }} {{ ucfirst($comptable['prenom']) }}</strong>
        @else
            <strong>Bienvenue</strong>
        @endif
    </div>
    <ul id="menuList">
        <li class="smenu"><a href="{{ route('chemin_voirVisiteur') }}">Gérer les visiteurs 2.A</a></li>
        <li class="smenu"><a href="{{ route('chemin_deconnexionG') }}">Déconnexion</a></li>
    </ul>
</div>
@endsection

@section('contenu3')
<div class="card-form">
    <h2 class="title-formulaire">📝 Modification du visiteur</h2>

    <form method="POST" action="{{ route('chemin_saveVisiteur', ['id' => $user['id'] ?? $user->id ?? '' ]) }}">
        @csrf
        <input type="hidden" name="id" value="{{ $user['id'] ?? $user->id ?? '' }}">

        @php
            $userData = is_array($user) ? $user : (array) $user;
            $fields = [
                'nom' => 'Nom',
                'prenom' => 'Prénom',
                'adresse' => 'Adresse',
                'cp' => 'Code Postal',
                'ville' => 'Ville',
                'DE' => 'Date d\'embauche'
            ];
        @endphp

        @foreach($fields as $key => $label)
            @php
                $value = old($key) 
                        ?? ($userData[$key] ?? ($key === 'DE' ? $userData['dateEmbauche'] ?? '' : ''));
            @endphp

            <div class="champ">
                <label for="{{ $key }}">{{ $label }} :</label>
                <input
                    type="{{ $key === 'DE' ? 'date' : 'text' }}"
                    id="{{ $key }}"
                    name="{{ $key }}"
                    value="{{ $value }}"
                    required>
            </div>
        @endforeach

        <div class="champ">
            <label for="login">Login :</label>
            <input type="text" id="login" name="login" value="{{ old('login') ?? ($userData['login'] ?? '') }}" required>
        </div>

        <div class="champ password-wrapper">
            <label for="mdp">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp" value="{{ old('mdp') ?? ($userData['mdp'] ?? '') }}" required>
            <button type="button" class="eye-icon" onclick="togglePassword()">
                👁️
            </button>
        </div>

        <div class="champ">
            <label for="email">Email :</label>
            <input type="text" id="email" name="email" value="{{ old('email') ?? ($userData['email'] ?? '') }}">
        </div>

        <div class="form-boutons">
            <button type="submit" class="btn-valider" title="Enregistrer les modifications">
                ✅ Valider
            </button>

            <a href="{{ route('chemin_voirVisiteur') }}" class="btn-annuler" title="Annuler et retourner à la liste">
                ❌ Annuler
            </a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
.card-form {
    width: 750px;
    margin: 50px auto;
    padding: 40px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', sans-serif;
}

.title-formulaire {
    font-size: 24px;
    color: #007BBA;
    margin-bottom: 30px;
    padding-left: 10px;
    border-left: 5px solid #007BBA;
}

.champ {
    margin-bottom: 22px;
    position: relative;
}
.champ label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #222;
}
.champ input {
    width: 100%;
    padding: 12px 15px;
    border-radius: 8px;
    font-size: 16px;
    border: 1px solid #ccc;
    background-color: #fff;
    transition: all 0.3s ease;
}
.champ input:focus {
    border-color: #007BBA;
    box-shadow: 0 0 5px rgba(0, 123, 186, 0.3);
    outline: none;
}

.password-wrapper .eye-icon {
    position: absolute;
    top: 50%;
    right: 14px;
    transform: translateY(-50%);
    font-size: 16px;
    height: 20px;
    width: 20px;
    color: #666;
    border: none;
    background: none;
    padding: 0;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.form-boutons {
    margin-top: 30px;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}
.btn-valider,
.btn-annuler {
    padding: 12px 26px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    cursor: pointer;
    white-space: nowrap;
    min-width: 150px;
    transition: background-color 0.3s ease;
}

.btn-valider {
    background-color: #007BBA;
    color: white;
    border: none;
}
.btn-valider:hover {
    background-color: #005f99;
}

.btn-annuler {
    background-color: #f4f4f4;
    color: #333;
    border: 1px solid #ccc;
}
.btn-annuler:hover {
    background-color: #e0e0e0;
}

/* 🌙 Mode sombre */
.dark-mode .card-form {
    background: #1f1f1f;
}
.dark-mode .champ label {
    color: #eee;
}
.dark-mode .champ input {
    background-color: #2c2c2c;
    color: #fff;
    border: 1px solid #555;
}
.dark-mode .btn-annuler {
    background-color: #333;
    color: #eee;
    border: 1px solid #555;
}
.dark-mode .btn-annuler:hover {
    background-color: #555;
}
.password-wrapper .eye-icon {
    position: absolute;
    top: 50%;
    right: 14px;
    transform: translateY(-50%);
    font-size: 16px;
    height: 20px;
    width: 20px;
    color: #666;
    border: none;
    background: none;
    padding: 0;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
</style>
@endpush

@push('scripts')
<script>
    function togglePassword() {
        const mdpInput = document.getElementById('mdp');
        mdpInput.type = (mdpInput.type === 'password') ? 'text' : 'password';
    }

    document.addEventListener("DOMContentLoaded", function () {
        const nomInput = document.getElementById("nom");
        const prenomInput = document.getElementById("prenom");
        const emailInput = document.getElementById("email");

        function genererEmail() {
            const nom = nomInput.value.trim().toLowerCase();
            const prenom = prenomInput.value.trim().toLowerCase();
            const random = Math.floor(100 + Math.random() * 900);

            if (nom && prenom) {
                const email = `${prenom}.${nom}${random}@gmail.com`;
                emailInput.value = email;
            }
        }

        nomInput.addEventListener("input", genererEmail);
        prenomInput.addEventListener("input", genererEmail);
    });
</script>
@endpush

