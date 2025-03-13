
    <h3>Liste des visiteurs :</h3>
    <div class="encadre">
        <table class="listeLegere">
            <tr>
                <td>Nom</td>
                <td>Prénom</td>
            </tr>
            @foreach($visiteurs as $unVisiteur)
          <tr>
            
			<td>{{$unVisiteur['nom']}}</td>
            <td>{{$unVisiteur['prenom']}}</td>
            <td>{{$unVisiteur['dateEmbauche']}}</td>

          </tr>
            @endforeach
        </table>
        
    </div>