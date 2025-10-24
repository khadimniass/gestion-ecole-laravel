@extends('layouts.app')
@section('title', 'Créer un Utilisateur')
@section('content')
    <h1>Créer un Utilisateur</h1>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="mb-3"><label class="form-label">Nom</label><input type="text" class="form-control" name="name" required></div>
            <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
            <div class="mb-3"><label class="form-label">Rôle</label>
                <select class="form-select" name="role" required>
                    <option value="etudiant">Étudiant</option>
                    <option value="enseignant">Enseignant</option>
                    <option value="coordinateur">Coordinateur</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="mb-3"><label class="form-label">Mot de passe</label><input type="password" class="form-control" name="password" required></div>
            <button type="submit" class="btn btn-success">Créer</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div></div>
@endsection
