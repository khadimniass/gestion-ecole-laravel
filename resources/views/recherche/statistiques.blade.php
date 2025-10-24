@extends('layouts.app')

@section('title', 'Statistiques')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-chart-bar"></i> Statistiques</h1>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="text-primary">{{ $stats['total_sujets'] ?? 0 }}</h2>
                    <p class="mb-0">Sujets</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="text-success">{{ $stats['total_pfes'] ?? 0 }}</h2>
                    <p class="mb-0">PFE</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="text-info">{{ $stats['total_etudiants'] ?? 0 }}</h2>
                    <p class="mb-0">Étudiants</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="text-warning">{{ $stats['total_enseignants'] ?? 0 }}</h2>
                    <p class="mb-0">Enseignants</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistiques détaillées</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Les statistiques détaillées seront affichées ici.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
