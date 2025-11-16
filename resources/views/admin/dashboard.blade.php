@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <h1 class="mb-4"><i class="fas fa-tachometer-alt"></i> Dashboard Administrateur</h1>

    <div class="row mb-4">
        <!-- Statistiques -->
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Étudiants</h6>
                            <h2 class="mb-0">{{ $stats['total_etudiants'] }}</h2>
                        </div>
                        <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Enseignants</h6>
                            <h2 class="mb-0">{{ $stats['total_enseignants'] }}</h2>
                        </div>
                        <i class="fas fa-chalkboard-teacher fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">PFE en cours</h6>
                            <h2 class="mb-0">{{ $stats['pfes_en_cours'] }}</h2>
                        </div>
                        <i class="fas fa-project-diagram fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Demandes en attente</h6>
                            <h2 class="mb-0">{{ $stats['demandes_en_attente'] }}</h2>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- PFE Récents -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5><i class="fas fa-history"></i> PFE Récents</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>N° PFE</th>
                                <th>Étudiants</th>
                                <th>Encadrant</th>
                                <th>Statut</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($pfesRecents as $pfe)
                                <tr>
                                    <td>{{ $pfe->numero_pfe }}</td>
                                    <td>{{ $pfe->etudiants->first()->name ?? 'N/A' }}</td>
                                    <td>{{ $pfe->encadrant->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $pfe->statut == 'en_cours' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($pfe->statut) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun PFE récent</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sujets en attente -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5><i class="fas fa-hourglass-half"></i> Sujets en Attente de Validation</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Proposé par</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($sujetsEnAttente as $sujet)
                                <tr>
                                    <td>{{ Str::limit($sujet->titre, 30) }}</td>
                                    <td>{{ $sujet->proposePar->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.sujets.validation') }}"
                                           class="btn btn-sm btn-primary">
                                            Valider
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">Aucun sujet en attente</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filières et Soutenances -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Filières</h5>
                    <a href="{{ route('admin.filieres.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-list"></i> Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Filière</th>
                                    <th>Niveau</th>
                                    <th class="text-center">Étudiants</th>
                                    <th class="text-center">Sujets</th>
                                    <th class="text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($filieres as $filiere)
                                    <tr>
                                        <td>
                                            <strong>{{ $filiere->nom }}</strong>
                                            <br><small class="text-muted">{{ $filiere->code }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $filiere->niveau == 'licence' ? 'info' : 'primary' }}">
                                                {{ ucfirst($filiere->niveau) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $filiere->etudiants_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $filiere->sujets_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $filiere->active ? 'success' : 'danger' }}">
                                                {{ $filiere->active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-3">
                                            <i class="fas fa-graduation-cap fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Aucune filière</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Soutenances à Venir</h5>
                    <a href="{{ route('admin.soutenances.index') }}" class="btn btn-sm btn-light">
                        <i class="fas fa-list"></i> Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>PFE</th>
                                    <th>Salle</th>
                                    <th>Heure</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($soutenancesProchaines as $pfe)
                                    <tr>
                                        <td>
                                            <i class="fas fa-calendar"></i>
                                            {{ $pfe->date_soutenance->format('d/m/Y') }}
                                        </td>
                                        <td>
                                            <strong>{{ $pfe->numero_pfe }}</strong>
                                            <br><small class="text-muted">{{ Str::limit($pfe->sujet->titre, 30) }}</small>
                                        </td>
                                        <td>{{ $pfe->salle_soutenance }}</td>
                                        <td>{{ $pfe->heure_soutenance }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">
                                            <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Aucune soutenance prévue</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5><i class="fas fa-tools"></i> Actions Rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.import.form') }}" class="btn btn-success">
                            <i class="fas fa-file-import"></i> Importer des utilisateurs
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Utilisateurs
                        </a>
                        <a href="{{ route('admin.filieres.index') }}" class="btn btn-info">
                            <i class="fas fa-graduation-cap"></i> Filières
                        </a>
                        <a href="#" class="btn btn-info">
                            <i class="fas fa"></i> Départements
                        </a>
                        <a href="{{ route('admin.sujets.validation') }}" class="btn btn-warning">
                            <i class="fas fa-check"></i> Valider des sujets
                        </a>
                        <a href="{{ route('admin.export.pfes') }}" class="btn btn-secondary">
                            <i class="fas fa-download"></i> Exporter les PFE
                        </a>
                        <a href="{{ route('admin.soutenances.index') }}" class="btn btn-danger">
                            <i class="fas fa-calendar"></i> Gérer les soutenances
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
