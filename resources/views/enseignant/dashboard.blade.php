@extends('layouts.app')

@section('title', 'Dashboard Enseignant')

@section('content')
    <h1 class="mb-4"><i class="fas fa-chalkboard-teacher"></i> Dashboard Enseignant</h1>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Mes Sujets</h6>
                    <h2>{{ $stats['mes_sujets'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>PFE Encadrés</h6>
                    <h2>{{ $stats['pfes_encadres'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6>En Cours</h6>
                    <h2>{{ $stats['pfes_en_cours'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Demandes Reçues</h6>
                    <h2>{{ $stats['demandes_recues'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Mes PFE en cours -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5>Mes PFE en Cours</h5>
                </div>
                <div class="card-body">
                    @forelse($mesPfes as $pfe)
                        <div class="mb-3 p-3 border rounded">
                            <h6>{{ $pfe->sujet->titre }}</h6>
                            <p class="mb-1">
                                <strong>Étudiants:</strong>
                                @foreach($pfe->etudiants as $etudiant)
                                    {{ $etudiant->name }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </p>
                            <p class="mb-1">
                                <strong>Progression:</strong>
                            <div class="progress">
                                @php
                                    $progression = now()->diffInDays($pfe->date_debut) /
                                                  $pfe->date_fin_prevue->diffInDays($pfe->date_debut) * 100;
                                    $progression = min(100, max(0, $progression));
                                @endphp
                                <div class="progress-bar" style="width: {{ $progression }}%">
                                    {{ round($progression) }}%
                                </div>
                            </div>
                            </p>
                            <a href="{{ route('pfes.show', $pfe) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Détails
                            </a>
                        </div>
                    @empty
                        <p class="text-muted">Aucun PFE en cours</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Demandes en attente -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5>Demandes en Attente</h5>
                </div>
                <div class="card-body">
                    @forelse($demandesEnAttente as $demande)
                        <div class="mb-3 p-3 border rounded">
                            <h6>{{ $demande->etudiant->name }}</h6>
                            <p class="mb-1">
                                <strong>Sujet:</strong>
                                {{ $demande->sujet ? $demande->sujet->titre : $demande->sujet_propose }}
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('enseignant.demandes.show', $demande) }}"
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                <form action="{{ route('enseignant.demandes.accepter', $demande) }}"
                                      method="POST" style="display: inline;">
                                    @csrf
                                    <button class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Accepter
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Aucune demande en attente</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
