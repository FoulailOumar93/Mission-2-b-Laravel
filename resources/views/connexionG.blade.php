@extends('modeles.visiteur')

@section('contenu1')
<div class="login-container">
    <center><h2>Connexion</h2></center>

    @if(session('erreur'))
        <div style="color:red; margin-bottom: 15px;">{{ session('erreur') }}</div>
    @endif

    <form action="{{ route('chemin_connexion') }}" method="POST">
        @csrf

        <label for="login">Login*</label>
        <div class="password-wrapper">
            <input type="text" name="login" id="login" required />
        </div>

        <label for="mdp">Mot de passe*</label>
        <div class="password-wrapper">
            <input type="password" name="mdp" id="mdp" required />
            <span class="eye-icon" onclick="togglePassword()" id="eye-icon" title="Afficher le mot de passe">👁️</span>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-valider">Valider</button>
            <button type="reset" class="btn-annuler">Annuler</button>
        </div>

        <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword() {
        const input = document.getElementById('mdp');
        const icon = document.getElementById('eye-icon');

        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = '🙈';
            icon.title = "Masquer le mot de passe";
        } else {
            input.type = 'password';
            icon.textContent = '👁️';
            icon.title = "Afficher le mot de passe";
        }
    }
</script>
@endpush
