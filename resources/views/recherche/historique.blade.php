@extends('layouts.app')

@section('title', 'Historique des Recherches')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-history"></i> Historique des Recherches</h1>
        <a href="{{ route('recherche.index') }}" class="btn btn-primary">
            <i class="fas fa-search"></i> Nouvelle Recherche
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <p class="text-muted">Vos recherches récentes apparaîtront ici.</p>
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aucune recherche récente</p>
            </div>
        </div>
    </div>
@endsection
