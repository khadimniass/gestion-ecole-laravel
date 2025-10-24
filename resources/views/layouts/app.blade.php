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
