@extends('layouts.app')

@section('title', 'Liste des PFE')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-project-diagram"></i> Liste des PFE</h1>
    </div>

    <!-- Filtres -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('pfes.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search"
                           placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="statut">
                        <option value="">Tous les statuts</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                        <option value="abandonne" {{ request('statut') == 'abandonne' ? 'selected' : '' }}>Abandonné</option>
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

    <!-- Liste des PFE -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>N° PFE</th>
                        <th>Sujet</th>
                        <th>Encadrant</th>
                        <th>Étudiants</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($pfes as $pfe)
                        <tr>
                            <td><strong>{{ $pfe->numero_pfe }}</strong></td>
                            <td>{{ $pfe->sujet->titre }}</td>
                            <td>{{ $pfe->encadrant->name }}</td>
                            <td>
                                @foreach($pfe->etudiants as $etudiant)
                                    <span class="badge bg-light text-dark">{{ $etudiant->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-{{
                                    $pfe->statut == 'en_cours' ? 'success' :
                                    ($pfe->statut == 'termine' ? 'info' : 'secondary')
                                }}">
                                    {{ ucfirst(str_replace('_', ' ', $pfe->statut)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('pfes.show', $pfe) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="mb-0">Aucun PFE trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $pfes->links() }}
            </div>
        </div>
    </div>
@endsection
