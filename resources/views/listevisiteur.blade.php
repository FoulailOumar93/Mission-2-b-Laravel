@extends('sommaireCompta')

@section('contenu1')
<h3>Liste des visiteurs :</h3>

@if(session('success'))
    <div class="message-success">{{ session('success') }}</div>
@endif

{{-- 🎯 FORMULAIRE DE TRI --}}
<form method="GET" action="{{ route('chemin_voirVisiteur') }}" class="tri-form">
    <label for="orderBy">Trier par :</label>
    <select name="orderBy" id="orderBy">
        <option value="id" {{ $orderBy === 'id' ? 'selected' : '' }}>ID</option>
        <option value="nom" {{ $orderBy === 'nom' ? 'selected' : '' }}>Nom</option>
        <option value="prenom" {{ $orderBy === 'prenom' ? 'selected' : '' }}>Prénom</option>
        <option value="login" {{ $orderBy === 'login' ? 'selected' : '' }}>Login</option>
        <option value="email" {{ $orderBy === 'email' ? 'selected' : '' }}>Email</option>
        <option value="adresse" {{ $orderBy === 'adresse' ? 'selected' : '' }}>Adresse</option>
        <option value="cp" {{ $orderBy === 'cp' ? 'selected' : '' }}>Code postal</option>
        <option value="ville" {{ $orderBy === 'ville' ? 'selected' : '' }}>Ville</option>
        <option value="dateEmbauche" {{ $orderBy === 'dateEmbauche' ? 'selected' : '' }}>Date d'embauche</option>
    </select>

    <select name="order" id="order">
        <option value="asc" {{ $ordre === 'asc' ? 'selected' : '' }}>⬆️ A-Z</option>
        <option value="desc" {{ $ordre === 'desc' ? 'selected' : '' }}>⬇️ Z-A</option>
    </select>

    <button type="submit" class="btn-trier">Trier</button>
</form>

<table class="styled-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Login</th>
            <th>Email</th>
            <th>Adresse</th>
            <th>Code Postal</th>
            <th>Ville</th>
            <th>Date d'embauche</th>
            <th colspan="2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lesVisiteurs as $unVisiteur)
        <tr>
            <td>{{ $unVisiteur['id'] }}</td>
            <td>{{ $unVisiteur['nom'] }}</td>
            <td>{{ $unVisiteur['prenom'] }}</td>
            <td>{{ $unVisiteur['login'] ?? '—' }}</td>
            <td>{{ $unVisiteur['email'] ?? '—' }}</td>
            <td>{{ $unVisiteur['adresse'] }}</td>
            <td>{{ $unVisiteur['cp'] }}</td>
            <td>{{ $unVisiteur['ville'] }}</td>
            <td>{{ \Carbon\Carbon::parse($unVisiteur['dateEmbauche'])->format('d/m/Y') }}</td>
            <td>
                <a href="{{ route('chemin_modifier', ['id' => $unVisiteur['id']]) }}" class="btn-modifier">🖊️ Modifier</a>
            </td>
            <td>
                <a href="{{ route('chemin_supprimer', ['id' => $unVisiteur['id']]) }}" class="btn-supprimer">🗑️ Supprimer</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="actions">
    <a href="{{ route('chemin_ajouterVisiteur') }}" class="btn-ajouter">+ Ajouter un visiteur</a>
    <a href="{{ route('generer.pdf.visiteurs', ['orderBy' => $orderBy, 'order' => $ordre]) }}" class="btn-pdf" target="_blank">📄 Télécharger PDF</a>
</div>
@endsection

@push('styles')
<style>
.tri-form {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin: 20px 0;
}

.tri-form label {
    font-weight: 600;
    color: #333;
}

.tri-form select {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}

.btn-trier {
    background-color: #007BBA;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-trier:hover {
    background-color: #005f9e;
}

.styled-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

.styled-table thead {
    background-color: #007BBA;
    color: #ffffff !important;
    text-transform: uppercase;
}

.styled-table th, .styled-table td {
    padding: 16px;
    border: 1px solid #e1e1e1;
    text-align: center;
    font-size: 15px;
}

.styled-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.styled-table tbody tr:hover {
    background-color: #f1f8ff;
}

.actions {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
}

a.btn-modifier,
a.btn-supprimer,
a.btn-ajouter,
a.btn-pdf,
a.btn-trier {
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    display: inline-block;
    transition: 0.3s ease-in-out;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    white-space: nowrap;
    border: none;
}

.btn-modifier {
    background-color: #3498db;
    color: #ffffff !important;
}
.btn-modifier:hover {
    background-color: #2980b9;
}

.btn-supprimer {
    background-color: #e74c3c;
    color: #ffffff !important;
}
.btn-supprimer:hover {
    background-color: #c0392b;
}

.btn-ajouter {
    background-color: #27ae60;
    color: #ffffff !important;
    font-size: 15px;
}
.btn-ajouter:hover {
    background-color: #1f8c4a;
}

.btn-pdf {
    background-color: #ecf0f1;
    color: #2c3e50;
    font-size: 14px;
}
.btn-pdf:hover {
    background-color: #d5dbdb;
}
/* 🌙 DARK MODE POUR LA LISTE DES VISITEURS */
body.dark-mode .tri-form label,
body.dark-mode .tri-form select {
    color: #e0e0e0;
    background-color: #2c2c2c;
    border: 1px solid #444;
}

body.dark-mode .btn-trier {
    background-color: #1565c0;
    color: #fff;
}

body.dark-mode .btn-trier:hover {
    background-color: #0d47a1;
}

body.dark-mode .styled-table thead {
    background-color: #1e88e5;
    color: #ffffff !important;
}

body.dark-mode .styled-table tbody tr:nth-child(even) {
    background-color: #1e1e1e;
}

body.dark-mode .styled-table tbody tr:hover {
    background-color: #2c2c2c;
}

body.dark-mode .styled-table td,
body.dark-mode .styled-table th {
    border: 1px solid #444;
    color: #e0e0e0;
}

body.dark-mode .btn-modifier {
    background-color: #42a5f5;
    color: #fff !important;
}
body.dark-mode .btn-modifier:hover {
    background-color: #1e88e5;
}

body.dark-mode .btn-supprimer {
    background-color: #ef5350;
    color: #fff !important;
}
body.dark-mode .btn-supprimer:hover {
    background-color: #d32f2f;
}

body.dark-mode .btn-ajouter {
    background-color: #66bb6a;
    color: #fff !important;
}
body.dark-mode .btn-ajouter:hover {
    background-color: #388e3c;
}

body.dark-mode .btn-pdf {
    background-color: #424242;
    color: #fff;
}
body.dark-mode .btn-pdf:hover {
    background-color: #616161;
}

body.dark-mode .message-success {
    background-color: #2e7d32;
    color: white;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
    text-align: center;
}

</style>
@endpush
