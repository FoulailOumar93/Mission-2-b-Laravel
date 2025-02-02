@extends('sommaireG')

@section('contenu1')
    <div id="contenu">
        <h1>Supprimer un visiteur</h1>



        <form action="{{ route('chemin_supprimerVisiteur', ['id' => $infoVisiteur['id']]) }}" method="post">
            @csrf <!-- Ajoutez cette ligne pour inclure le jeton CSRF -->
            <button type="submit">Oui, supprimer</button>
        </form>


        <a href="{{ route('chemin_gererVisiteur') }}">Annuler</a>
    </div>
@endsection
