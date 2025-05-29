<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Visiteurs</title>
    <style>
        @page {
            margin: 30px;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }
        h2 {
            text-align: center;
            color: #007BBA;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #666;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #007BBA;
            color: #fff;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f4f8fb;
        }
        .footer {
            margin-top: 40px;
            font-size: 11px;
            color: #333;
            text-align: center;
        }
        .total {
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Liste des visiteurs</h2>

    @php
        $colonnes = [
            'id' => 'ID',
            'nom' => 'Nom',
            'prenom' => 'Prénom',
            'email' => 'Email',
            'adresse' => 'Adresse',
            'cp' => 'Code Postal',
            'ville' => 'Ville',
            'dateEmbauche' => 'Date d\'embauche'
        ];
        $fleche = $ordre === 'desc' ? ' 🔽' : ' 🔼';
    @endphp

    <table>
        <thead>
            <tr>
                @foreach($colonnes as $col => $titre)
                    <th>
                        {{ $titre }}{!! $orderBy === $col ? $fleche : '' !!}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($lesVisiteurs as $visiteur)
            <tr>
                <td>{{ $visiteur['id'] }}</td>
                <td>{{ $visiteur['nom'] }}</td>
                <td>{{ $visiteur['prenom'] }}</td>
                <td>{{ $visiteur['email'] ?? '—' }}</td>
                <td>{{ $visiteur['adresse'] }}</td>
                <td>{{ $visiteur['cp'] }}</td>
                <td>{{ $visiteur['ville'] }}</td>
                <td>{{ date('d/m/Y', strtotime($visiteur['dateEmbauche'])) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total : {{ count($lesVisiteurs) }} visiteur{{ count($lesVisiteurs) > 1 ? 's' : '' }}
    </div>

    <hr style="margin-top: 50px; border: none; border-top: 1px solid #ccc;">

    <div class="footer">
        Document généré automatiquement le 
        {{ ucfirst(\Carbon\Carbon::now()->setTimezone('Europe/Paris')->translatedFormat('l d F Y à H\hi')) }} 
        (heure de Paris)
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <img src="{{ public_path('images/logoIco.jpg') }}" alt="Logo GSB" style="height: 60px;">
    </div>
</body>
</html>
