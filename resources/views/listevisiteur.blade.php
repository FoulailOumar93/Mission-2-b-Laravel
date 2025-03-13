@extends('sommaire')

@section('contenu1')
    <h3>Liste des visiteurs :</h3>
    <div class="encadre">
        <table class="listeLegere">
            <tr>
                <td>Nom</td>
                <td>Prénom</td>
            </tr>
            @foreach($lesVisiteurs as $unVisiteur)
          <tr>
            
			<td>{{$unVisiteur['nom']}}</td>
            <td>{{$unVisiteur['prenom']}}</td>

            <td>
                {{-- <a href="{{ url('edit/'.$unVisiteur['id']) }}" class="btn btn-success">Edit</a> --}}

                <form action="{{ route('chemin_modifier', ['id' => $unVisiteur['id']]) }}" method="get">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$unVisiteur['id']}}">
                    <button type="submit" name="submit">Modifier</button>
                </form>
            </td>

            <!--<td>
                <form action="{{ route('chemin_supprimerVisiteur') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{$unVisiteur['id']}}">
                    <button type="submit" name="submit">Supprimer</button>
                </form>
            </td>-->
          </tr>
            @endforeach
        </table>
        <form action="{{ route('chemin_ajouterVisiteur')}}">
            <label for="add">Ajouter un visiteur</label>
            <button type="submit" name="submit">Ajouter</button>
        </form>

        <a href="{{ route('generer.pdf.visiteurs') }}" target="_blank">Générer la liste des Visiteurs en PDF</a>

    </div>
@endsection
