@extends ('modeles/visiteur')
  @stack('styles')
     @stack('scripts')
@section('menu')
<!-- Division pour le sommaire -->
<div id="menuGauche">
    <div id="infosUtil">
    </div>  
    <ul id="menuList">
        <li>
            <strong>
    Bonjour 
    {{ isset($visiteur) ? ucfirst($visiteur['nom']) . ' ' . ucfirst($visiteur['prenom']) 
    : (isset($comptable) ? ucfirst($comptable['nom']) . ' ' . ucfirst($comptable['prenom']) : 'Utilisateur') }}
</strong>

        </li>
        <li class="smenu">
            <a href="{{ route('chemin_gestionFrais') }}" title="Saisie fiche de frais">Saisie fiche de frais</a>
        </li>
        <li class="smenu">
            <a href="{{ route('chemin_selectionMois') }}" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
        </li>
        </li>
        <li class="smenu">
            <a href="{{ route('chemin_deconnexion') }}" title="Se déconnecter">Déconnexion</a>
        </li>
    </ul>
</div>
@endsection
