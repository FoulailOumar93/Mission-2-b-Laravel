@extends ('sommaire')

@section('contenu1')
@push('styles')
<style>
.frais-container {
    max-width: 800px;
    margin: 40px auto;
    padding: 40px;
    background-color: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    font-family: 'Segoe UI', sans-serif;
}

.dark-mode .frais-container {
    background-color: #1e1e1e;
    color: #f5f5f5;
}

.frais-container h2 {
    font-size: 24px;
    color: #2c3e50;
    margin-bottom: 24px;
    border-left: 5px solid #007BBA;
    padding-left: 14px;
}

.dark-mode .frais-container h2 {
    color: #90caf9;
    border-left: 5px solid #1565c0;
}

fieldset {
    border: 1px solid #ccc;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 24px;
}

.dark-mode fieldset {
    border: 1px solid #555;
}

legend {
    font-weight: bold;
    font-size: 18px;
    color: #34495e;
}

.dark-mode legend {
    color: #ccc;
}

.frais-group {
    margin-bottom: 18px;
}

.frais-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #333;
}

.dark-mode .frais-group label {
    color: #eee;
}

.frais-group input[type="text"] {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    background-color: #fff;
    color: #000;
}

.dark-mode .frais-group input[type="text"] {
    background-color: #2c2c2c;
    color: #fff;
    border: 1px solid #666;
}

.piedForm {
    text-align: right;
    margin-top: 20px;
}

.piedForm input[type="submit"],
.piedForm input[type="reset"] {
    padding: 10px 22px;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    font-size: 14px;
    cursor: pointer;
    margin-left: 10px;
    transition: 0.3s ease;
}

#ok {
    background-color: #27ae60;
    color: white;
}
#ok:hover {
    background-color: #219150;
}

#annuler {
    background-color: #bdc3c7;
    color: #2c3e50;
}
#annuler:hover {
    background-color: #95a5a6;
}

.dark-mode #annuler {
    background-color: #555;
    color: #eee;
}
.dark-mode #annuler:hover {
    background-color: #777;
}
</style>
@endpush

<div class="frais-container">
    <h2>Renseigner ma fiche de frais du mois {{ $numMois }}-{{ $numAnnee }}</h2>

    <form method="post" action="{{ route('chemin_sauvegardeFrais') }}">
        @csrf

        <fieldset>
            <legend>Éléments forfaitisés</legend>

            @if($erreurs)
                @include('msgerreurs', ['erreurs' => $erreurs])
            @endif

            @if($message)
                @include('message', ['message' => $message])
            @endif

            @foreach ($lesFrais as $key => $frais)
                <div class="frais-group">
                    <input type="hidden" name="lesLibFrais[]" value="{{ $method === 'GET' ? $frais['libelle'] : $lesLibFrais[$loop->index] }}">
                    <label>{{ $method === 'GET' ? $frais['libelle'] : $lesLibFrais[$loop->index] }}</label>
                    <input type="text" name="lesFrais[{{ $method === 'GET' ? $frais['idfrais'] : $key }}]" value="{{ $method === 'GET' ? $frais['quantite'] : $frais }}" required>
                </div>
            @endforeach
        </fieldset>

        <div class="piedForm">
            <input id="ok" type="submit" value="Valider" />
            <input id="annuler" type="reset" value="Annuler" />
        </div>
    </form>
</div>
@endsection
