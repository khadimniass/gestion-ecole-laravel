@extends('layouts.app')
@section('title', 'Gestion des Filières')
@section('content')
    <h1><i class="fas fa-graduation-cap"></i> Gestion des Filières</h1>
    <div class="card"><div class="card-body">
        <a href="{{ route('admin.filieres.create') }}" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Nouvelle Filière</a>
        <table class="table"><thead><tr><th>Nom</th><th>Code</th><th>Actions</th></tr></thead><tbody>
        @forelse($filieres ?? [] as $filiere)
            <tr><td>{{ $filiere->nom }}</td><td>{{ $filiere->code }}</td><td><a href="{{ route('admin.filieres.edit', $filiere) }}" class="btn btn-sm btn-warning">Modifier</a></td></tr>
        @empty
            <tr><td colspan="3" class="text-center">Aucune filière</td></tr>
        @endforelse
        </tbody></table>
    </div></div>
@endsection
