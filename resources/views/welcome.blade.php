@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="jumbotron bg-light p-5 rounded">
                <h1 class="display-4">
                    <i class="fas fa-graduation-cap"></i>
                    Système de Gestion des PFE
                </h1>
                <p class="lead">
                    Bienvenue sur la plateforme de gestion et suivi des Projets de Fin d'Études
                </p>
                <hr class="my-4">

                @guest
                    <p>Connectez-vous pour accéder à votre espace personnel.</p>
                    <div class="d-flex gap-2">
                        <a class="btn btn-primary btn-lg" href="{{ route('login') }}" role="button">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </a>
                        <a class="btn btn-success btn-lg" href="{{ route('register') }}" role="button">
                            <i class="fas fa-user-plus"></i> S'inscrire
                        </a>
                    </div>
                @else
                    <p>Bienvenue, <strong>{{ auth()->user()->name }}</strong> !</p>
                    <a class="btn btn-primary btn-lg" href="{{ route('dashboard') }}" role="button">
                        <i class="fas fa-tachometer-alt"></i> Accéder au Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-book fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Sujets Disponibles</h5>
                    <p class="card-text">Consultez les sujets de PFE proposés par les enseignants</p>
                    <a href="{{ route('sujets.index') }}" class="btn btn-outline-primary">
                        Voir les sujets
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Encadrement</h5>
                    <p class="card-text">Système de demande et d'affectation des encadrants</p>
                    @auth
                        @if(auth()->user()->estEtudiant())
                            <a href="{{ route('etudiant.demandes.create') }}" class="btn btn-outline-success">
                                Faire une demande
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                    <h5 class="card-title">Suivi & Statistiques</h5>
                    <p class="card-text">Tableaux de bord et statistiques en temps réel</p>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-info">
                            Mon dashboard
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
@endsection
