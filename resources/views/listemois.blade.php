@extends('sommaire')

@section('contenu1')
    @push('styles')
    <style>
        .fiche-container {
            max-width: 640px;
            margin: 60px auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            font-family: 'Segoe UI', sans-serif;
        }

        .fiche-container h2 {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 25px;
            border-left: 5px solid #007acc;
            padding-left: 12px;
        }

        .fiche-container h3 {
            font-size: 18px;
            font-weight: 600;
            color: #34495e;
            margin-bottom: 15px;
        }

        .fiche-container label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
            color: #333;
        }

        .fiche-container select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            margin-bottom: 30px;
        }

        .btn-actions {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
        }

        #ok, #annuler {
            padding: 12px 24px;
            font-weight: bold;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
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
    </style>
    @endpush

    <div class="fiche-container">
        <h2>Mes fiches de frais</h2>
        <h3>Mois à sélectionner :</h3>

        <form action="{{ route('chemin_listeFrais') }}" method="post">
            {{ csrf_field() }}

            <label for="lstMois">Mois :</label>
            <select id="lstMois" name="lstMois">
                @foreach($lesMois as $mois)
                    <option value="{{ $mois['mois'] }}" {{ $mois['mois'] == $leMois ? 'selected' : '' }}>
                        {{ $mois['numMois'] }}/{{ $mois['numAnnee'] }}
                    </option>
                @endforeach
            </select>

            <div class="btn-actions">
                <input id="ok" type="submit" value="Valider" />
                <input id="annuler" type="reset" value="Effacer" />
            </div>
        </form>
    </div>
@endsection
