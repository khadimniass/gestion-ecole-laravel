@extends('layouts.app')

@section('title', 'Gestion des Départements')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-building"></i> Gestion des Départements</h2>
        @can('create', App\Models\Departement::class)
            <a href="{{ route('admin.departements.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nouveau Département
            </a>
        @endcan
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

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Liste des Départements ({{ $departements->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Nom</th>
                            <th>Filières</th>
                            <th>Enseignants</th>
                            <th>Coordinateurs</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departements as $departement)
                            <tr class="{{ !$departement->actif ? 'table-secondary' : '' }}">
                                <td><strong>{{ $departement->code }}</strong></td>
                                <td>
                                    <strong>{{ $departement->nom }}</strong>
                                    @if($departement->description)
                                        <br><small class="text-muted">{{ Str::limit($departement->description, 50) }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $departement->filieres_count }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $departement->enseignants_count }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $departement->coordinateurs_count }}</span>
                                </td>
                                <td>
                                    @if($departement->actif)
                                        <span class="badge bg-success"><i class="fas fa-check"></i> Actif</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-times"></i> Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @can('view', $departement)
                                            <a href="{{ route('admin.departements.show', $departement) }}"
                                               class="btn btn-sm btn-info"
                                               title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan

                                        @can('update', $departement)
                                            <a href="{{ route('admin.departements.edit', $departement) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('delete', $departement)
                                            <form action="{{ route('admin.departements.destroy', $departement) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce département ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun département trouvé.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $departements->links() }}
    </div>
</div>
@endsection
