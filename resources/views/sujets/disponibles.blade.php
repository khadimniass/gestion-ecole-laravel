@extends('layouts.app')

@section('title', 'Sujets Disponibles')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-book"></i> Sujets PFE Disponibles</h1>
        <a href="{{ route('etudiant.demandes.create') }}" class="btn btn-success">
            <i class="fas fa-envelope"></i> Faire une Demande
        </a>
    </div>

    <!-- Filtres -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('etudiant.sujets.disponibles') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search"
                           placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="niveau">
                        <option value="">Tous les niveaux</option>
                        <option value="licence" {{ request('niveau') == 'licence' ? 'selected' : '' }}>Licence</option>
                        <option value="master" {{ request('niveau') == 'master' ? 'selected' : '' }}>Master</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="statut">
                        <option value="">Tous les statuts</option>
                        <option value="propose" {{ request('statut') == 'propose' ? 'selected' : '' }}>Proposé</option>
                        <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Validé</option>
                        <option value="affecte" {{ request('statut') == 'affecte' ? 'selected' : '' }}>Affecté</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des sujets -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Titre</th>
                        <th>Proposé par</th>
                        <th>Niveau</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sujets as $sujet)
                        <tr>
                            <td>{{ $sujet->code_sujet }}</td>
                            <td>
                                <strong>{{ $sujet->titre }}</strong><br>
                                <small>
                                    @foreach($sujet->motsCles as $mot)
                                        <span class="badge bg-secondary">{{ $mot->mot }}</span>
                                    @endforeach
                                </small>
                            </td>
                            <td>{{ $sujet->proposePar->name }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($sujet->niveau_requis) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{
                                    $sujet->statut == 'valide' ? 'success' :
                                    ($sujet->statut == 'propose' ? 'warning' : 'secondary')
                                }}">
                                    {{ ucfirst($sujet->statut) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('sujets.show', $sujet) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @can('update', $sujet)
                                    <a href="{{ route('sujets.edit', $sujet) }}"
                                       class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="mb-0">Aucun sujet trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $sujets->links() }}
            </div>
        </div>
    </div>
@endsection
