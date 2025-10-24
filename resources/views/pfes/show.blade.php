@extends('layouts.app')

@section('title', 'Détails du PFE')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-project-diagram"></i> Détails du PFE</h1>
        <a href="{{ route('pfes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations du PFE</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong class="text-muted">N° PFE :</strong>
                        <h4>{{ $pfe->numero_pfe }}</h4>
                    </div>

                    <div class="mb-3">
                        <strong class="text-muted">Sujet :</strong>
                        <p>
                            <a href="{{ route('sujets.show', $pfe->sujet) }}">
                                {{ $pfe->sujet->titre }}
                            </a>
                        </p>
                    </div>

                    <div class="mb-3">
                        <strong class="text-muted">Encadrant :</strong>
                        <p>{{ $pfe->encadrant->name }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="text-muted">Étudiants :</strong>
                        <ul class="list-unstyled">
                            @foreach($pfe->etudiants as $etudiant)
                                <li>
                                    <span class="badge bg-light text-dark">
                                        {{ $etudiant->pivot->role_dans_groupe == 'chef' ? '👑' : '👤' }}
                                        {{ $etudiant->name }}
                                    </span>
                                    @if($etudiant->pivot->note_individuelle)
                                        - Note: {{ $etudiant->pivot->note_individuelle }}/20
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-3">
                        <strong class="text-muted">Période :</strong>
                        <p>
                            Du {{ $pfe->date_debut->format('d/m/Y') }}
                            au {{ $pfe->date_fin_prevue->format('d/m/Y') }}
                        </p>
                    </div>

                    @if($pfe->date_soutenance)
                        <div class="mb-3">
                            <strong class="text-muted">Soutenance :</strong>
                            <p>
                                {{ $pfe->date_soutenance->format('d/m/Y') }}
                                @if($pfe->heure_soutenance)
                                    à {{ $pfe->heure_soutenance }}
                                @endif
                                @if($pfe->salle_soutenance)
                                    - Salle : {{ $pfe->salle_soutenance }}
                                @endif
                            </p>
                        </div>
                    @endif

                    @if($pfe->note_finale)
                        <div class="mb-3">
                            <strong class="text-muted">Note finale :</strong>
                            <h4>{{ $pfe->note_finale }}/20</h4>
                        </div>
                    @endif

                    @if($pfe->observations)
                        <div class="mb-3">
                            <strong class="text-muted">Observations :</strong>
                            <p>{{ $pfe->observations }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents -->
            @if($pfe->rapport_file || $pfe->presentation_file)
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-file"></i> Documents</h5>
                    </div>
                    <div class="card-body">
                        @if($pfe->rapport_file)
                            <p>
                                <a href="{{ route('download.rapport', $pfe) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-file-pdf"></i> Télécharger le rapport
                                </a>
                            </p>
                        @endif

                        @if($pfe->presentation_file)
                            <p>
                                <a href="{{ route('download.presentation', $pfe) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-file-powerpoint"></i> Télécharger la présentation
                                </a>
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Barre latérale -->
        <div class="col-md-4">
            <!-- Statut -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-flag"></i> Statut</h5>
                </div>
                <div class="card-body text-center">
                    <span class="badge bg-{{
                        $pfe->statut == 'en_cours' ? 'success' :
                        ($pfe->statut == 'termine' ? 'info' : 'secondary')
                    }} fs-5">
                        {{ ucfirst(str_replace('_', ' ', $pfe->statut)) }}
                    </span>
                </div>
            </div>

            <!-- Année universitaire -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar"></i> Année universitaire</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-center">{{ $pfe->anneeUniversitaire->annee }}</p>
                </div>
            </div>

            <!-- Actions -->
            @can('update', $pfe)
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-cog"></i> Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('enseignant.pfes.edit', $pfe) }}" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection
