@extends('layouts.app')
@section('title', 'Gérer le Jury')
@section('content')
    <h1>Gérer le Jury de Soutenance</h1>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.soutenances.jury.update', $pfe) }}" method="POST">
            @csrf
            <p><strong>PFE:</strong> {{ $pfe->numero_pfe }}</p>
            <div class="mb-3"><label>Membres du jury (sélectionnez des enseignants)</label>
                <p class="text-muted">La fonctionnalité de sélection du jury sera implémentée ici.</p>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('admin.soutenances.index') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div></div>
@endsection
