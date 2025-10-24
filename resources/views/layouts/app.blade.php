{{-- ======================================== --}}
{{-- 1. Layout Principal: layouts/app.blade.php --}}
{{-- ======================================== --}}
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion PFE') - {{ config('app.name') }}</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-graduation-cap"></i> Gestion PFE
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sujets.index') }}">
                            <i class="fas fa-book"></i> Sujets
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pfes.index') }}">
                            <i class="fas fa-project-diagram"></i> PFE
                        </a>
                    </li>

                    @if(auth()->user()->estEtudiant())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('etudiant.demandes.index') }}">
                                <i class="fas fa-envelope"></i> Mes Demandes
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->estEnseignant())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('enseignant.demandes.index') }}">
                                <i class="fas fa-inbox"></i> Demandes Reçues
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('recherche.index') }}">
                            <i class="fas fa-search"></i> Recherche
                        </a>
                    </li>
                @endauth
            </ul>

            <ul class="navbar-nav">
                @auth
                    <!-- Notifications -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="notifDropdown"
                           role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            @if(auth()->user()->notifications()->nonLues()->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ auth()->user()->notifications()->nonLues()->count() }}
                                    </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @forelse(auth()->user()->notifications()->nonLues()->latest()->take(5)->get() as $notif)
                                <li>
                                    <a class="dropdown-item" href="{{ route('notifications.index') }}">
                                        <strong>{{ $notif->titre }}</strong><br>
                                        <small>{{ Str::limit($notif->message, 50) }}</small>
                                    </a>
                                </li>
                            @empty
                                <li><span class="dropdown-item">Aucune notification</span></li>
                            @endforelse
                        </ul>
                    </li>

                    <!-- Profil -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown"
                           role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="fas fa-user-circle"></i> Mon Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Inscription</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Année Active -->
@if(isset($anneeActive))
    <div class="bg-info text-white text-center py-1">
        <small>Année Universitaire : {{ $anneeActive->annee }}</small>
    </div>
@endif

<!-- Contenu Principal -->
<main class="py-4">
    <div class="container">
        <!-- Messages Flash -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<!-- Footer -->
<footer class="bg-dark text-white mt-5 py-3">
    <div class="container text-center">
        <p class="mb-0">&copy; 2024 Système de Gestion PFE - Aissata Elhadj BA</p>
    </div>
</footer>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

{{-- ======================================== --}}
{{-- 2. Dashboard Étudiant: etudiant/dashboard.blade.php --}}
{{-- ======================================== --}}
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

{{-- ======================================== --}}
{{-- 3. Formulaire de Demande: etudiant/demandes/create.blade.php --}}
{{-- ======================================== --}}
@extends('layouts.app')

@section('title', 'Nouvelle Demande d\'Encadrement')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><i class="fas fa-envelope"></i> Nouvelle Demande d'Encadrement</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('etudiant.demandes.store') }}" method="POST" id="demandeForm">
                        @csrf

                        <!-- Choix de l'enseignant -->
                        <div class="mb-3">
                            <label for="enseignant_id" class="form-label">
                                <i class="fas fa-user-tie"></i> Enseignant Encadrant <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('enseignant_id') is-invalid @enderror"
                                    id="enseignant_id" name="enseignant_id" required>
                                <option value="">-- Choisir un enseignant --</option>
                                @foreach($enseignants as $enseignant)
                                    <option value="{{ $enseignant->id }}"
                                        {{ old('enseignant_id') == $enseignant->id ? 'selected' : '' }}>
                                        {{ $enseignant->name }}
                                        @if($enseignant->specialite)
                                            ({{ $enseignant->specialite }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('enseignant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type de demande -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-question-circle"></i> Type de Demande <span class="text-danger">*</span>
                            </label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type_demande"
                                           id="type_existant" value="sujet_existant"
                                        {{ old('type_demande', 'sujet_existant') == 'sujet_existant' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_existant">
                                        Choisir un sujet existant
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type_demande"
                                           id="type_proposition" value="proposition_sujet"
                                        {{ old('type_demande') == 'proposition_sujet' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_proposition">
                                        Proposer mon propre sujet
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Sujet existant -->
                        <div id="sujet_existant_div" class="mb-3">
                            <label for="sujet_id" class="form-label">
                                <i class="fas fa-book"></i> Sujet <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('sujet_id') is-invalid @enderror"
                                    id="sujet_id" name="sujet_id">
                                <option value="">-- Choisir un sujet --</option>
                                @foreach($sujets as $sujet)
                                    <option value="{{ $sujet->id }}"
                                            {{ old('sujet_id') == $sujet->id ? 'selected' : '' }}
                                            data-description="{{ $sujet->description }}">
                                        {{ $sujet->titre }} ({{ $sujet->proposePar->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('sujet_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Description du sujet sélectionné -->
                            <div id="sujet_description" class="mt-2 p-3 bg-light rounded" style="display: none;">
                                <strong>Description:</strong>
                                <p id="description_text" class="mb-0"></p>
                            </div>
                        </div>

                        <!-- Proposition de sujet -->
                        <div id="proposition_sujet_div" style="display: none;">
                            <div class="mb-3">
                                <label for="sujet_propose" class="form-label">
                                    <i class="fas fa-lightbulb"></i> Titre du Sujet Proposé <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('sujet_propose') is-invalid @enderror"
                                       id="sujet_propose" name="sujet_propose"
                                       value="{{ old('sujet_propose') }}"
                                       placeholder="Ex: Développement d'une application mobile...">
                                @error('sujet_propose')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description_sujet" class="form-label">
                                    <i class="fas fa-align-left"></i> Description du Sujet <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description_sujet') is-invalid @enderror"
                                          id="description_sujet" name="description_sujet" rows="4"
                                          placeholder="Décrivez votre projet en détail...">{{ old('description_sujet') }}</textarea>
                                @error('description_sujet')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Lettre de motivation -->
                        <div class="mb-3">
                            <label for="motivation" class="form-label">
                                <i class="fas fa-pen"></i> Lettre de Motivation <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('motivation') is-invalid @enderror"
                                      id="motivation" name="motivation" rows="5" required
                                      placeholder="Expliquez pourquoi vous souhaitez travailler sur ce sujet et avec cet enseignant...">{{ old('motivation') }}</textarea>
                            @error('motivation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 50 caractères</small>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('etudiant.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Envoyer la Demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du type de demande
            const typeExistant = document.getElementById('type_existant');
            const typeProposition = document.getElementById('type_proposition');
            const sujetExistantDiv = document.getElementById('sujet_existant_div');
            const propositionSujetDiv = document.getElementById('proposition_sujet_div');

            function toggleTypeDemande() {
                if (typeExistant.checked) {
                    sujetExistantDiv.style.display = 'block';
                    propositionSujetDiv.style.display = 'none';
                    document.getElementById('sujet_id').required = true;
                    document.getElementById('sujet_propose').required = false;
                    document.getElementById('description_sujet').required = false;
                } else {
                    sujetExistantDiv.style.display = 'none';
                    propositionSujetDiv.style.display = 'block';
                    document.getElementById('sujet_id').required = false;
                    document.getElementById('sujet_propose').required = true;
                    document.getElementById('description_sujet').required = true;
                }
            }

            typeExistant.addEventListener('change', toggleTypeDemande);
            typeProposition.addEventListener('change', toggleTypeDemande);
            toggleTypeDemande();

            // Afficher la description du sujet sélectionné
            const sujetSelect = document.getElementById('sujet_id');
            const sujetDescriptionDiv = document.getElementById('sujet_description');
            const descriptionText = document.getElementById('description_text');

            sujetSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    descriptionText.textContent = selectedOption.dataset.description;
                    sujetDescriptionDiv.style.display = 'block';
                } else {
                    sujetDescriptionDiv.style.display = 'none';
                }
            });
        });
    </script>
@endpush
