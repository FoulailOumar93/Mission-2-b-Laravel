@extends ('sommaire')
@section('contenu3')

<div id="contenu">
    <form method="post"  action="{{ route('chemin_sauvegarderVisiteur') }}">
                    {{ csrf_field() }} <!-- laravel va ajouter un champ caché avec un token -->
                    <input type="hidden" name="id" value="{{ $visiteur->id ?? '' }}">
        <div class="corpsForm">
            <fieldset>
                <legend>Veuillez écrire les informations du visiteur</legend>
                {{-- @includeWhen($erreurs != null, 'msgerreurs', ['erreurs' => $erreurs]) --}}
                {{-- @includeWhen($message != "", 'message', ['message' => $message]) --}}
                    <p>
                    <div>
                    
                    <input type = "text" name = "nom" value="$visiteur->nom ?? old('nom')" required> 
                    <label name = "nom" for="nom">Nom : </label>
                    
                    </div>
                    </br>

                    <div>
                    <input type="text" name = "prenom" required>
                    <label name = "prenom" for="prenom">Prénom : </label>  
                    </div>   
                    </br>

                    <div>
                    <input type="text" name = "adresse" required>
                    <label name = "adresse" for="adresse">Adresse : </label>
                    </div>
                    </br>

                    <div>
                    <input type="text" name = "cp" required>
                    <label name = "cp" for="cp">Code Postal : </label>
                    </div>
                    </br>

                    <div>
                    <input type="text" name = "ville" required>
                    <label name = "ville" for="ville">Ville : </label>
                    </div>
                    </br>
                    
                    <div>
                    <input type="text" name = "DE" required>
                    <label name = "DE" for="DE">Date Embauche : </label>
                    </div>
                    </p>
            </fieldset>
        </div>
        <div class="piedForm">
            <p>
            <input id="ok" type="submit" value="Valider" size="20" />
            <input id="annuler" type="reset" value="Annuler" size="20" />
            </p> 
        </div>
    </form>
@endsection