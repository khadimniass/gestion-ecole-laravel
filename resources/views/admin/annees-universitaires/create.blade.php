@extends('layouts.app')
@section('title', 'Créer une Année')
@section('content')
    <h1>Créer une Année Universitaire</h1>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.annees-universitaires.store') }}" method="POST">
            @csrf
            <div class="mb-3"><label>Année (ex: 2024-2025)</label><input type="text" class="form-control" name="annee" required></div>
            <div class="mb-3"><label>Date début</label><input type="date" class="form-control" name="date_debut" required></div>
            <div class="mb-3"><label>Date fin</label><input type="date" class="form-control" name="date_fin" required></div>
            <button type="submit" class="btn btn-success">Créer</button>
            <a href="{{ route('admin.annees-universitaires.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div></div>
@endsection
