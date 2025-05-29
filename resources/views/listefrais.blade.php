@extends ('listemois')

@section('contenu2')
@push('styles')
<style>
    .fiche-details {
        max-width: 900px;
        margin: 40px auto;
        background-color: #ffffff;
        padding: 35px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        font-family: 'Segoe UI', sans-serif;
    }

    .fiche-details h3 {
        font-size: 22px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 20px;
        border-left: 5px solid #007acc;
        padding-left: 12px;
    }

    .fiche-details p {
        font-size: 16px;
        color: #34495e;
        margin-bottom: 20px;
    }

    .fiche-details strong {
        color: #2c3e50;
    }

    table.listeLegere {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        background-color: #f9f9f9;
        border-radius: 8px;
        overflow: hidden;
    }

    table.listeLegere caption {
        text-align: left;
        font-weight: 600;
        color: #007acc;
        margin-bottom: 10px;
        font-size: 18px;
    }

    table.listeLegere th,
    table.listeLegere td {
        padding: 14px;
        text-align: center;
        border: 1px solid #e0e0e0;
        font-size: 15px;
    }

    table.listeLegere th {
        background-color: #007acc;
        color: white;
        font-weight: 600;
        text-transform: uppercase;
    }

    .qteForfait {
        background-color: #ffffff;
        font-weight: 500;
    }
</style>
@endpush

<div class="fiche-details">
    <h3>Fiche de frais du mois {{ $numMois }}-{{ $numAnnee }} :</h3>

    <p>
        État : <strong>{{ $libEtat }}</strong> depuis le <strong>{{ $dateModif }}</strong><br>
        Montant validé : <strong>{{ $montantValide }} €</strong>
    </p>

    <table class="listeLegere">
        <caption>Éléments forfaitisés</caption>
        <tr>
            @foreach($lesFraisForfait as $unFraisForfait)
                <th>{{ $unFraisForfait['libelle'] }}</th>
            @endforeach
        </tr>
        <tr>
            @foreach($lesFraisForfait as $unFraisForfait)
                <td class="qteForfait">{{ $unFraisForfait['quantite'] }}</td>
            @endforeach
        </tr>
    </table>
</div>
@endsection
