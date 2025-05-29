@extends('modeles.admin')

@stack('styles')
@stack('scripts')

@section('menu')
<!-- ✅ Menu latéral gauche -->
<div id="menuGauche">
    <div id="infosUtil">
        <strong>Bonjour {{ isset($comptable) ? ucfirst($comptable['nom']) . ' ' . ucfirst($comptable['prenom']) : 'Comptable' }}</strong>
    </div>

    <ul id="menuList">
        <li class="smenu">
            <a href="{{ route('chemin_voirVisiteur') }}">📋 Gérer les visiteurs 2.A</a>
        </li>
        <li class="smenu">
            <a href="{{ route('chemin_deconnexion') }}">🚪 Déconnexion</a>
        </li>
    </ul>
</div>
@endsection
