@extends('layouts.app')
@section('title', 'Créer une Filière')
@section('content')
    <h1>Créer une Filière</h1>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.filieres.store') }}" method="POST">
            @csrf
            <div class="mb-3"><label>Nom</label><input type="text" class="form-control" name="nom" required></div>
            <div class="mb-3"><label>Code</label><input type="text" class="form-control" name="code" required></div>
            <button type="submit" class="btn btn-success">Créer</button>
            <a href="{{ route('admin.filieres.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div></div>
@endsection
