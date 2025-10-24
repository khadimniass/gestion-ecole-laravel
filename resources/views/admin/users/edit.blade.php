@extends('layouts.app')
@section('title', 'Modifier l\'Utilisateur')
@section('content')
    <h1>Modifier l'Utilisateur</h1>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3"><label class="form-label">Nom</label><input type="text" class="form-control" name="name" value="{{ $user->name }}" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="{{ $user->email }}" required></div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div></div>
@endsection
