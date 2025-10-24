@extends('layouts.app')

@section('title', 'Recherche')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-search"></i> Recherche</h1>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('recherche.index') }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control form-control-lg" name="q"
                               placeholder="Rechercher un sujet, un PFE, un étudiant..."
                               value="{{ request('q') }}">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-lg" name="type">
                            <option value="">Tous les types</option>
                            <option value="sujets" {{ request('type') == 'sujets' ? 'selected' : '' }}>Sujets</option>
                            <option value="pfes" {{ request('type') == 'pfes' ? 'selected' : '' }}>PFE</option>
                            <option value="etudiants" {{ request('type') == 'etudiants' ? 'selected' : '' }}>Étudiants</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(request('q'))
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    Résultats pour "{{ request('q') }}"
                    @if(isset($total))
                        <span class="badge bg-primary">{{ $total }} résultat(s)</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if(isset($sujets) && $sujets->count() > 0)
                    <h6 class="mt-3"><i class="fas fa-book"></i> Sujets</h6>
                    <ul class="list-group mb-3">
                        @foreach($sujets as $sujet)
                            <li class="list-group-item">
                                <a href="{{ route('sujets.show', $sujet) }}">
                                    {{ $sujet->titre }}
                                </a>
                                <span class="badge bg-secondary float-end">{{ $sujet->code_sujet }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if(isset($pfes) && $pfes->count() > 0)
                    <h6 class="mt-3"><i class="fas fa-project-diagram"></i> PFE</h6>
                    <ul class="list-group mb-3">
                        @foreach($pfes as $pfe)
                            <li class="list-group-item">
                                <a href="{{ route('pfes.show', $pfe) }}">
                                    {{ $pfe->numero_pfe }} - {{ $pfe->sujet->titre }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if(!isset($sujets) || ($sujets->count() == 0 && (!isset($pfes) || $pfes->count() == 0)))
                    <p class="text-center text-muted py-4">Aucun résultat trouvé</p>
                @endif
            </div>
        </div>
    @endif
@endsection
