@extends('layouts.app')

@section('title', 'Importer des Données')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-file-import"></i> Import de Données</h1>
        <a href="{{ route('admin.import.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('erreurs') && count(session('erreurs')) > 0)
        <div class="alert alert-warning alert-dismissible fade show">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Détails des erreurs d'import</h5>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Ligne</th>
                            <th>Identifiant</th>
                            <th>Erreur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('erreurs') as $erreur)
                            <tr>
                                <td>{{ $erreur['ligne'] }}</td>
                                <td>{{ $erreur['matricule'] ?? $erreur['nom'] ?? 'N/A' }}</td>
                                <td>{{ $erreur['erreur'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-graduate"></i> Importer des Étudiants</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.import.etudiants') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="file_etudiants" class="form-label">
                                Fichier CSV <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                   class="form-control @error('file') is-invalid @enderror"
                                   id="file_etudiants"
                                   name="file"
                                   accept=".csv,.xlsx,.xls"
                                   required>
                            <small class="text-muted">Format : CSV, Excel (max 2MB)</small>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="filiere_id" class="form-label">
                                Filière <span class="text-danger">*</span>
                            </label>
                            <select name="filiere_id"
                                    id="filiere_id"
                                    class="form-select @error('filiere_id') is-invalid @enderror"
                                    required>
                                <option value="">-- Sélectionner une filière --</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}">
                                        {{ $filiere->nom }} ({{ $filiere->niveau }})
                                    </option>
                                @endforeach
                            </select>
                            @error('filiere_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="niveau_etude" class="form-label">
                                Niveau d'étude <span class="text-danger">*</span>
                            </label>
                            <select name="niveau_etude"
                                    id="niveau_etude"
                                    class="form-select @error('niveau_etude') is-invalid @enderror"
                                    required>
                                <option value="">-- Sélectionner un niveau --</option>
                                <option value="L1">Licence 1</option>
                                <option value="L2">Licence 2</option>
                                <option value="L3">Licence 3</option>
                                <option value="M1">Master 1</option>
                                <option value="M2">Master 2</option>
                            </select>
                            @error('niveau_etude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <strong>Format du CSV :</strong><br>
                            <code>matricule,nom,prenom,email,telephone</code><br>
                            <small>Le mot de passe par défaut sera le matricule de l'étudiant</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Importer les Étudiants
                            </button>
                            <a href="{{ route('admin.import.template', 'etudiants') }}"
                               class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-download"></i> Télécharger le modèle CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-chalkboard-teacher"></i> Importer des Enseignants</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.import.enseignants') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="file_enseignants" class="form-label">
                                Fichier CSV <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                   class="form-control @error('file') is-invalid @enderror"
                                   id="file_enseignants"
                                   name="file"
                                   accept=".csv,.xlsx,.xls"
                                   required>
                            <small class="text-muted">Format : CSV, Excel (max 2MB)</small>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="departement" class="form-label">
                                Département <span class="text-danger">*</span>
                            </label>
                            <select name="departement"
                                    id="departement"
                                    class="form-select @error('departement') is-invalid @enderror"
                                    required>
                                <option value="">-- Sélectionner un département --</option>
                                <option value="Informatique">Informatique</option>
                                <option value="Mathématiques">Mathématiques</option>
                                <option value="Physique">Physique</option>
                                <option value="Chimie">Chimie</option>
                                <option value="Biologie">Biologie</option>
                            </select>
                            @error('departement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <strong>Format du CSV :</strong><br>
                            <code>nom,prenom,email,telephone,specialite</code><br>
                            <small>Un mot de passe aléatoire sera généré et doit être communiqué à l'enseignant</small>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-upload"></i> Importer les Enseignants
                            </button>
                            <a href="{{ route('admin.import.template', 'enseignants') }}"
                               class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-download"></i> Télécharger le modèle CSV
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
