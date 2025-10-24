@extends('layouts.app')

@section('title', 'Dashboard Étudiant')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">
                <i class="fas fa-home"></i> Bienvenue, {{ auth()->user()->name }}
            </h1>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Mon PFE -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5><i class="fas fa-project-diagram"></i> Mon PFE</h5>
                </div>
                <div class="card-body">
                    @if($monPfe)
                        <h6>{{ $monPfe->sujet->titre }}</h6>
                        <p class="text-muted">N° {{ $monPfe->numero_pfe }}</p>

                        <div class="mb-3">
                            <strong>Encadrant:</strong> {{ $monPfe->encadrant->name }}<br>
                            <strong>Statut:</strong>
                            <span class="badge bg-{{ $monPfe->statut == 'en_cours' ? 'success' : 'info' }}">
                            {{ ucfirst($monPfe->statut) }}
                        </span><br>
                            <strong>Date début:</strong> {{ $monPfe->date_debut->format('d/m/Y') }}<br>
                            <strong>Date fin prévue:</strong> {{ $monPfe->date_fin_prevue->format('d/m/Y') }}
                        </div>

                        @if($monPfe->statut == 'en_cours')
                            <div class="d-grid gap-2">
                                <a href="{{ route('etudiant.mon-pfe.index') }}" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> Voir Détails
                                </a>
                                @if(!$monPfe->rapport_file)
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uploadRapportModal">
                                        <i class="fas fa-upload"></i> Déposer Rapport
                                    </button>
                                @endif
                            </div>
                        @endif
                    @else
                        <p class="text-muted">Vous n'avez pas encore de PFE assigné.</p>

                        @if($maDemande)
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Votre demande est <strong>{{ $maDemande->statut }}</strong>
                            </div>
                        @else
                            <a href="{{ route('etudiant.demandes.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Faire une Demande d'Encadrement
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5><i class="fas fa-chart-bar"></i> Informations</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-graduation-cap fa-2x text-primary mb-2"></i>
                                <h6>Filière</h6>
                                <p>{{ auth()->user()->filiere->nom }}</p>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-layer-group fa-2x text-success mb-2"></i>
                                <h6>Niveau</h6>
                                <p>{{ auth()->user()->niveau_etude }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <i class="fas fa-id-card fa-2x text-warning mb-2"></i>
                                <h6>Matricule</h6>
                                <p>{{ auth()->user()->matricule }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <i class="fas fa-users fa-2x text-danger mb-2"></i>
                                <h6>Groupe</h6>
                                <p>{{ $monGroupe ? $monGroupe->nom_groupe : 'Aucun' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sujets Disponibles -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5><i class="fas fa-book"></i> Sujets Disponibles</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Proposé par</th>
                                <th>Niveau</th>
                                <th>Nb. Étudiants</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($sujetsDisponibles as $sujet)
                                <tr>
                                    <td>
                                        <strong>{{ $sujet->titre }}</strong><br>
                                        <small class="text-muted">
                                            @foreach($sujet->motsCles as $mot)
                                                <span class="badge bg-secondary">{{ $mot->mot }}</span>
                                            @endforeach
                                        </small>
                                    </td>
                                    <td>{{ $sujet->proposePar->name }}</td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucfirst($sujet->niveau_requis) }}
                                        </span>
                                    </td>
                                    <td>{{ $sujet->nombre_etudiants_max }}</td>
                                    <td>
                                        <a href="{{ route('sujets.show', $sujet) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Aucun sujet disponible pour le moment
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('etudiant.sujets.disponibles') }}" class="btn btn-primary">
                            <i class="fas fa-list"></i> Voir Tous les Sujets
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Rapport -->
    @if($monPfe && $monPfe->statut == 'en_cours')
        <div class="modal fade" id="uploadRapportModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('etudiant.mon-pfe.upload-rapport') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Déposer le Rapport</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="rapport" class="form-label">Fichier PDF du Rapport</label>
                                <input type="file" class="form-control" id="rapport" name="rapport"
                                       accept=".pdf" required>
                                <small class="text-muted">Format PDF uniquement, max 10MB</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Déposer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
