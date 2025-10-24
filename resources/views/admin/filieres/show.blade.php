@extends('layouts.app')
@section('title', 'Détails Filière')
@section('content')
    <h1>Détails de la Filière</h1>
    <div class="card"><div class="card-body">
        <p><strong>Nom:</strong> {{ $filiere->nom }}</p>
        <p><strong>Code:</strong> {{ $filiere->code }}</p>
        <a href="{{ route('admin.filieres.index') }}" class="btn btn-secondary">Retour</a>
    </div></div>
@endsection
