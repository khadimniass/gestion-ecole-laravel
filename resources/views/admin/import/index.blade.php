@extends('layouts.app')
@section('title', 'Import de Données')
@section('content')
    <h1><i class="fas fa-file-import"></i> Import de Données</h1>
    <div class="card"><div class="card-body">
        <p>Importez des données depuis des fichiers Excel ou CSV.</p>
        <a href="{{ route('admin.import.form') }}" class="btn btn-primary">Importer des données</a>
    </div></div>
@endsection
