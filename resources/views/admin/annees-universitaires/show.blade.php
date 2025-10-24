@extends('layouts.app')
@section('title', 'Détails Année')
@section('content')
    <h1>Détails de l'Année Universitaire</h1>
    <div class="card"><div class="card-body">
        <p><strong>Année:</strong> {{ $annee->annee }}</p>
        <p><strong>Active:</strong> {{ $annee->active ? 'Oui' : 'Non' }}</p>
        <a href="{{ route('admin.annees-universitaires.index') }}" class="btn btn-secondary">Retour</a>
    </div></div>
@endsection
