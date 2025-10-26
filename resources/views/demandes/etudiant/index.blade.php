@extends('layouts.app')

@section('title', 'Mes Demandes d\'Encadrement')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-envelope"></i> Mes Demandes d'Encadrement</h1>
        <a href="{{ route('etudiant.demandes.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nouvelle Demande
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Enseignant</th>
                        <th>Sujet</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($demandes as $demande)
                        <tr>
                            <td>{{ $demande->enseignant->name }}</td>
                            <td>
                                @if($demande->sujet)
                                    {{ \Str::limit($demande->sujet->titre, 50) }}
                                @else
                                    <em class="text-muted">Sujet non spécifié</em>
                                @endif
                            </td>
                            <td>{{ $demande->created_at->format('d/m/Y') }}</td>
                            <td>
                                @if($demande->statut == 'accepte')
                                    <span class="badge bg-success">Acceptée</span>
                                @elseif($demande->statut == 'refuse')
                                    <span class="badge bg-danger">Refusée</span>
                                @else
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('etudiant.demandes.show', $demande) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($demande->statut == 'en_attente')
                                    <form action="{{ route('etudiant.demandes.destroy', $demande) }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <p class="mb-0">Aucune demande envoyée</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($demandes->hasPages())
                <div class="mt-3">
                    {{ $demandes->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
