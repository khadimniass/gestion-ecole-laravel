@extends('layouts.app')
@section('title', 'Modifier l\'Année')
@section('content')
    <h1>Modifier l'Année Universitaire</h1>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.annees-universitaires.update', $annee) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3"><label>Année</label><input type="text" class="form-control" name="annee" value="{{ $annee->annee }}" required></div>
            <div class="mb-3"><label>Active</label>
                <select class="form-select" name="active">
                    <option value="1" {{ $annee->active ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ !$annee->active ? 'selected' : '' }}>Non</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('admin.annees-universitaires.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div></div>
@endsection
