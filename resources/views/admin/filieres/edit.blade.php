@extends('layouts.app')
@section('title', 'Modifier la Filière')
@section('content')
    <h1>Modifier la Filière</h1>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.filieres.update', $filiere) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3"><label>Nom</label><input type="text" class="form-control" name="nom" value="{{ $filiere->nom }}" required></div>
            <div class="mb-3"><label>Code</label><input type="text" class="form-control" name="code" value="{{ $filiere->code }}" required></div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('admin.filieres.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div></div>
@endsection
