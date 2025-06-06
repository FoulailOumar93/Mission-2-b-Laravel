@extends('sommaireCompta')

@section('contenu1')
<style>
.confirm-container {
    max-width: 600px;
    margin: 60px auto;
    background-color: #ffffff;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', sans-serif;
    text-align: center;
}

.titre {
    font-size: 28px;
    color: #c0392b;
    margin-bottom: 20px;
}

.question {
    font-size: 18px;
    margin-bottom: 30px;
    color: #2c3e50;
}

.btn-container {
    display: flex;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
}

.btn-supprimer {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 30px;
    min-width: 140px;
    font-size: 16px;
    font-weight: 500;
    background-color: #e74c3c;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.25s ease, transform 0.15s ease;
}

.btn-supprimer:hover {
    background-color: #c0392b;
    transform: translateY(-2px);
}

.btn-annuler {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 30px;
    min-width: 140px;
    font-size: 16px;
    font-weight: 500;
    background-color: #bdc3c7;
    color: #2c3e50;
    border-radius: 8px;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.25s ease, transform 0.15s ease;
}

.btn-annuler:hover {
    background-color: #95a5a6;
    transform: translateY(-2px);
}

.emoji {
    font-size: 18px;
    line-height: 1;
    vertical-align: middle;
}
</style>

<div class="confirm-container">
    <h1 class="titre"><span class="emoji">⚠️</span> Supprimer un visiteur</h1>

    <p class="question">
        Es-tu sûr de vouloir supprimer <strong>{{ $visiteur['prenom'] }} {{ $visiteur['nom'] }}</strong> ?
    </p>

    <form action="{{ route('chemin_supprimerVisiteur', ['id' => $visiteur['id']]) }}" method="POST" onsubmit="return confirm('⚠️ Es-tu sûr de vouloir supprimer ce visiteur ? Cette action est irréversible.')">
        @csrf
        @method('DELETE')

        <div class="btn-container">
            <button type="submit" class="btn-supprimer">
                <span class="emoji">🗑️</span> Supprimer
            </button>

            <a href="{{ route('chemin_voirVisiteur') }}" class="btn-annuler">
                <span class="emoji">❌</span> Annuler
            </a>
        </div>
    </form>
</div>
@endsection
