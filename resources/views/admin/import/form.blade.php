@extends('layouts.app')
@section('title', 'Formulaire d\'Import')
@section('content')
    <h1><i class="fas fa-file-import"></i> Formulaire d'Import</h1>
    <div class="card"><div class="card-body">
        <form action="{{ route('admin.import.etudiants') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3"><label>Type d'import</label>
                <select class="form-select" name="type">
                    <option value="etudiants">Étudiants</option>
                    <option value="enseignants">Enseignants</option>
                </select>
            </div>
            <div class="mb-3"><label>Fichier</label><input type="file" class="form-control" name="file" required></div>
            <button type="submit" class="btn btn-success">Importer</button>
            <a href="{{ route('admin.import.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div></div>
@endsection
