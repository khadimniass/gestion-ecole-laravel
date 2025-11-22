@extends('layouts.app')

@section('title', 'Détails du Département')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-building"></i> {{ $departement->nom }}</h2>
        <div>
            @can('update', $departement)
                <a href="{{ route('admin.departements.edit', $departement) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Modifier
                </a>
            @endcan
            <a href="{{ route('admin.departements.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Informations générales -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations Générales</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Code :</strong> {{ $departement->code }}</p>
                    <p><strong>Nom :</strong> {{ $departement->nom }}</p>
                    <p><strong>Statut :</strong>
                        @if($departement->actif)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Créé le :</strong> {{ $departement->created_at->format('d/m/Y à H:i') }}</p>
                    <p><strong>Modifié le :</strong> {{ $departement->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            @if($departement->description)
                <hr>
                <p class="mb-0"><strong>Description :</strong><br>{{ $departement->description }}</p>
            @endif
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                    <h3>{{ $departement->filieres_count }}</h3>
                    <p class="mb-0">Filières</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-chalkboard-teacher fa-3x mb-3"></i>
                    <h3>{{ $departement->enseignants_count }}</h3>
                    <p class="mb-0">Enseignants</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-user-tie fa-3x mb-3"></i>
                    <h3>{{ $departement->coordinateurs_count }}</h3>
                    <p class="mb-0">Coordinateurs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filières -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Filières ({{ $filieres->count() }})</h5>
        </div>
        <div class="card-body">
            @if($filieres->isEmpty())
                <p class="text-muted mb-0">Aucune filière dans ce département.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Nom</th>
                                <th>Niveau</th>
                                <th>Étudiants</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($filieres as $filiere)
                                <tr>
                                    <td>{{ $filiere->code }}</td>
                                    <td>{{ $filiere->nom }}</td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($filiere->niveau) }}</span></td>
                                    <td><span class="badge bg-primary">{{ $filiere->etudiants_count }}</span></td>
                                    <td>
                                        @if($filiere->actif)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Coordinateurs -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-user-tie"></i> Coordinateurs ({{ $coordinateurs->count() }})</h5>
        </div>
        <div class="card-body">
            @if($coordinateurs->isEmpty())
                <p class="text-muted mb-0">Aucun coordinateur dans ce département.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coordinateurs as $coordinateur)
                                <tr>
                                    <td>{{ $coordinateur->name }}</td>
                                    <td>{{ $coordinateur->email }}</td>
                                    <td>{{ $coordinateur->telephone ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Enseignants -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-chalkboard-teacher"></i> Enseignants ({{ $enseignants->count() }})</h5>
        </div>
        <div class="card-body">
            @if($enseignants->isEmpty())
                <p class="text-muted mb-0">Aucun enseignant dans ce département.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Spécialité</th>
                                <th>Téléphone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enseignants as $enseignant)
                                <tr>
                                    <td>{{ $enseignant->name }}</td>
                                    <td>{{ $enseignant->email }}</td>
                                    <td>{{ $enseignant->specialite ?? 'N/A' }}</td>
                                    <td>{{ $enseignant->telephone ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
