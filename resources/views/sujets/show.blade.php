@extends('layouts.app')

@section('title', 'Détails du Sujet PFE')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-book"></i> Détails du Sujet PFE</h1>
        <a href="{{ route('sujets.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations du Sujet</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong class="text-muted">Code du sujet :</strong>
                        <p class="mb-0">{{ $sujet->code_sujet }}</p>
                    </div>

                    <div class="mb-3">
                        <strong class="text-muted">Titre :</strong>
                        <h4>{{ $sujet->titre }}</h4>
                    </div>

                    <div class="mb-3">
                        <strong class="text-muted">Description :</strong>
                        <p>{{ $sujet->description }}</p>
                    </div>

                    @if($sujet->objectifs)
                        <div class="mb-3">
                            <strong class="text-muted">Objectifs :</strong>
                            <p>{{ $sujet->objectifs }}</p>
                        </div>
                    @endif

                    @if($sujet->technologies)
                        <div class="mb-3">
                            <strong class="text-muted">Technologies :</strong>
                            <p>{{ $sujet->technologies }}</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong class="text-muted">Mots-clés :</strong><br>
                        @forelse($sujet->motsCles as $mot)
                            <span class="badge bg-secondary me-1">{{ $mot->mot }}</span>
                        @empty
                            <span class="text-muted">Aucun mot-clé</span>
                        @endforelse
                    </div>

                    <div class="mb-3">
                        <strong class="text-muted">Niveau requis :</strong>
                        <span class="badge bg-info">{{ ucfirst($sujet->niveau_requis) }}</span>
                    </div>

                    <div class="mb-3">
                        <strong class="text-muted">Nombre d'étudiants max :</strong>
                        <span class="badge bg-secondary">{{ $sujet->nombre_etudiants_max }}</span>
                    </div>

                    @if($sujet->filiere)
                        <div class="mb-3">
                            <strong class="text-muted">Filière :</strong>
                            <p class="mb-0">{{ $sujet->filiere->nom }}</p>
                        </div>
                    @endif

                    @if($sujet->departement)
                        <div class="mb-3">
                            <strong class="text-muted">Département :</strong>
                            <p class="mb-0">{{ $sujet->departement }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- PFEs liés -->
            @if($sujet->pfes->count() > 0)
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-project-diagram"></i> PFE(s) Affecté(s)</h5>
                    </div>
                    <div class="card-body">
                        @foreach($sujet->pfes as $pfe)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6>{{ $pfe->numero_pfe }}</h6>
                                    <p class="mb-2">
                                        <strong>Encadrant :</strong> {{ $pfe->encadrant->name }}<br>
                                        <strong>Statut :</strong>
                                        <span class="badge bg-{{ $pfe->statut == 'en_cours' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($pfe->statut) }}
                                        </span>
                                    </p>
                                    <p class="mb-0">
                                        <strong>Étudiants :</strong>
                                        @foreach($pfe->etudiants as $etudiant)
                                            <span class="badge bg-light text-dark">{{ $etudiant->name }}</span>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        @endforeach
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
                        $sujet->statut == 'valide' ? 'success' :
                        ($sujet->statut == 'propose' ? 'warning' : 'secondary')
                    }} fs-5">
                        {{ ucfirst($sujet->statut) }}
                    </span>
                </div>
            </div>

            <!-- Proposé par -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Proposé par</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $sujet->proposePar->name }}</strong></p>
                    <p class="mb-0 text-muted">{{ $sujet->proposePar->email }}</p>
                    @if($sujet->proposePar->departement)
                        <p class="mb-0 text-muted">
                            <small>{{ $sujet->proposePar->departement }}</small>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Validé par -->
            @if($sujet->validePar)
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-check"></i> Validé par</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>{{ $sujet->validePar->name }}</strong></p>
                        <p class="mb-0 text-muted">
                            <small>Le {{ $sujet->date_validation->format('d/m/Y') }}</small>
                        </p>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-cog"></i> Actions</h5>
                </div>
                <div class="card-body">
                    @can('update', $sujet)
                        <a href="{{ route('enseignant.sujets.edit', $sujet) }}" class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    @endcan

                    @if(auth()->user()->estEtudiant() && $sujet->estDisponible())
                        <a href="{{ route('etudiant.demandes.create', ['sujet_id' => $sujet->id]) }}"
                           class="btn btn-success w-100 mb-2">
                            <i class="fas fa-paper-plane"></i> Demander l'encadrement
                        </a>
                    @endif

                    @can('valider', $sujet)
                        @if($sujet->statut == 'propose')
                            <form action="{{ route('admin.sujets.valider', $sujet) }}" method="POST" class="mb-2">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check"></i> Valider
                                </button>
                            </form>

                            <form action="{{ route('admin.sujets.rejeter', $sujet) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100"
                                        onclick="return confirm('Êtes-vous sûr de vouloir rejeter ce sujet ?')">
                                    <i class="fas fa-times"></i> Rejeter
                                </button>
                            </form>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection
