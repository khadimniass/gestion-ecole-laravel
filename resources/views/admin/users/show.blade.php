@extends('layouts.app')
@section('title', 'Détails Utilisateur')
@section('content')
    <h1>Détails de l'Utilisateur</h1>
    <div class="card"><div class="card-body">
        <p><strong>Nom:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Rôle:</strong> {{ $user->role }}</p>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Retour</a>
    </div></div>
@endsection
