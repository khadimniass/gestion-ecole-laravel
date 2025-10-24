@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-user-circle"></i> Mon Profil</h1>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Modifier
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong class="text-muted">Nom complet :</strong>
                        </div>
                        <div class="col-md-9">
                            {{ auth()->user()->name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong class="text-muted">Email :</strong>
                        </div>
                        <div class="col-md-9">
                            {{ auth()->user()->email }}
                        </div>
                    </div>

                    @if(auth()->user()->matricule)
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong class="text-muted">Matricule :</strong>
                            </div>
                            <div class="col-md-9">
                                {{ auth()->user()->matricule }}
                            </div>
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong class="text-muted">Rôle :</strong>
                        </div>
                        <div class="col-md-9">
                            <span class="badge bg-primary">{{ ucfirst(auth()->user()->role) }}</span>
                        </div>
                    </div>

                    @if(auth()->user()->telephone)
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong class="text-muted">Téléphone :</strong>
                            </div>
                            <div class="col-md-9">
                                {{ auth()->user()->telephone }}
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->departement)
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong class="text-muted">Département :</strong>
                            </div>
                            <div class="col-md-9">
                                {{ auth()->user()->departement }}
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->filiere)
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong class="text-muted">Filière :</strong>
                            </div>
                            <div class="col-md-9">
                                {{ auth()->user()->filiere->nom }}
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->niveau_etude)
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong class="text-muted">Niveau :</strong>
                            </div>
                            <div class="col-md-9">
                                {{ auth()->user()->niveau_etude }}
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->specialite)
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <strong class="text-muted">Spécialité :</strong>
                            </div>
                            <div class="col-md-9">
                                {{ auth()->user()->specialite }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-key"></i> Sécurité</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">
                        <small class="text-muted">Dernière connexion</small><br>
                        <strong>Aujourd'hui</strong>
                    </p>
                    <a href="{{ route('profile.edit') }}#password" class="btn btn-warning w-100">
                        <i class="fas fa-lock"></i> Changer le mot de passe
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistiques</h5>
                </div>
                <div class="card-body">
                    @if(auth()->user()->estEnseignant())
                        <p class="mb-2">
                            <strong>{{ auth()->user()->pfesEncadrés->count() }}</strong> PFE encadrés
                        </p>
                        <p class="mb-0">
                            <strong>{{ auth()->user()->sujetsProposés->count() }}</strong> Sujets proposés
                        </p>
                    @elseif(auth()->user()->estEtudiant())
                        <p class="mb-0">
                            <strong>{{ auth()->user()->pfesEtudiant->count() }}</strong> PFE réalisé(s)
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
