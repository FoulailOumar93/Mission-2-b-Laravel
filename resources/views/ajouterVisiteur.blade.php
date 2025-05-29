@extends('modeles.admin')

@section('menu')
<div id="menuGauche">
    <div id="infosUtil">
        <strong>
            Bonjour {{ ucfirst($comptable['nom']) }} {{ ucfirst($comptable['prenom']) }}
        </strong>
    </div>
    <ul id="menuList">
        <li class="smenu">
            <a href="{{ route('chemin_voirVisiteur') }}">Gérer les visiteurs 2.A</a>
        </li>
        <li class="smenu">
            <a href="{{ route('chemin_deconnexionG') }}">Déconnexion</a>
        </li>
    </ul>
</div>
@endsection

@section('contenu3')
<div class="form-container">
  <h2 class="form-title">➕ Ajouter un nouveau visiteur</h2>

  @if ($errors->any())
      <div class="form-erreurs">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  <form method="POST" action="{{ route('chemin_sauvegarderVisiteur') }}">
    @csrf

    <div class="champ">
      <label for="nom">Nom :</label>
      <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required>
    </div>

    <div class="champ">
      <label for="prenom">Prénom :</label>
      <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
    </div>

    <div class="champ">
      <label for="login">Login :</label>
      <input type="text" id="login" name="login" value="{{ old('login') }}" required>
    </div>

    <div class="champ password-wrapper">
      <label for="mdp">Mot de passe :</label>
      <input type="password" id="mdp" name="mdp" required>
      <button type="button" class="eye-icon" onclick="togglePassword()">👁️</button>
    </div>

    <div class="champ">
      <label for="adresse">Adresse :</label>
      <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}" required>
    </div>

    <div class="champ">
      <label for="cp">Code Postal :</label>
      <input type="text" id="cp" name="cp" value="{{ old('cp') }}" required>
    </div>

    <div class="champ">
      <label for="ville">Ville :</label>
      <input type="text" id="ville" name="ville" value="{{ old('ville') }}" required>
    </div>

    <div class="champ">
      <label for="DE">Date d'embauche :</label>
      <input type="date" id="DE" name="DE" value="{{ old('DE') }}" required>
    </div>

    <div class="champ">
      <label for="email">Email :</label>
      <input type="text" id="email" name="email" value="{{ old('email') }}">
    </div>

    <div class="form-boutons">
      <button type="submit" class="btn-valider">
        ✅ Valider
      </button>
      <a href="{{ route('chemin_voirVisiteur') }}" class="btn-annuler">❌ Annuler</a>
    </div>
  </form>
</div>
@endsection

@push('styles')
<style>
.form-container {
  max-width: 640px;
  margin: 50px auto;
  padding: 40px;
  background-color: #fff;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.form-title {
  text-align: center;
  font-size: 24px;
  font-weight: bold;
  color: #007acc;
  margin-bottom: 30px;
}

.champ {
  margin-bottom: 20px;
  position: relative;
}

.champ label {
  font-weight: bold;
  margin-bottom: 6px;
  color: #333;
}

.champ input {
  width: 100%;
  padding: 12px 16px;
  font-size: 16px;
  border-radius: 8px;
  border: 1px solid #ccc;
  background-color: #f9f9f9;
  color: #000;
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
  display: flex;
  justify-content: space-between;
  gap: 16px;
  margin-top: 30px;
}

.btn-valider,
.btn-annuler {
  flex: 1;
  font-weight: bold;
  font-size: 16px;
  padding: 12px 0;
  border-radius: 8px;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
}

.btn-valider {
  background-color: #27ae60;
  color: white;
  border: none;
}

.btn-valider:hover {
  background-color: #219150;
}

.btn-annuler {
  background-color: #e0e0e0;
  color: #333;
  border: 1px solid #ccc;
}

.btn-annuler:hover {
  background-color: #ccc;
}

@media (prefers-color-scheme: dark) {
  .form-container {
    background-color: #1f1f1f;
    color: #eee;
  }
  .champ label { color: #ccc; }
  .champ input {
    background-color: #2e2e2e;
    color: #fff;
    border: 1px solid #555;
  }
  .btn-valider {
    background-color: #2ecc71;
    color: #000;
  }
  .btn-annuler {
    background-color: #444;
    color: #eee;
    border: none;
  }
}
/* 🌞 Mode clair explicite */
body:not(.dark-mode) .form-container {
    background: #ffffff;
    color: #000;
}

body:not(.dark-mode) .champ input {
    background-color: #fdfdfd;
    color: #000;
    border: 1px solid #ccc;
}

body:not(.dark-mode) .champ label {
    color: #333;
}

body:not(.dark-mode) .btn-valider {
    background-color: #27ae60;
    color: #fff;
}

body:not(.dark-mode) .btn-annuler {
    background-color: #f4f4f4;
    color: #333;
    border: 1px solid #ccc;
}

</style>
@endpush

@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const nom = document.getElementById("nom");
    const prenom = document.getElementById("prenom");
    const email = document.getElementById("email");

    function genererEmail() {
      const n = nom.value.trim().toLowerCase();
      const p = prenom.value.trim().toLowerCase();
      const rand = Math.floor(100 + Math.random() * 900);
      if (n && p) email.value = `${p}.${n}${rand}@gmail.com`;
    }

    nom.addEventListener("input", genererEmail);
    prenom.addEventListener("input", genererEmail);
  });

  function togglePassword() {
    const input = document.getElementById("mdp");
    input.type = input.type === "password" ? "text" : "password";
  }
</script>
@endpush
