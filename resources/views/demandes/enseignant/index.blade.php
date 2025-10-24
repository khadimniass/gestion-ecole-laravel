@extends('layouts.app')

@section('title', 'Demandes d\'Encadrement Reçues')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-inbox"></i> Demandes d'Encadrement Reçues</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Sujet</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($demandes as $demande)
                        <tr>
                            <td>{{ $demande->etudiant->name }}</td>
                            <td>
                                @if($demande->sujet)
                                    {{ \Str::limit($demande->sujet->titre, 50) }}
                                @else
                                    <em class="text-muted">Sujet non spécifié</em>
                                @endif
                            </td>
                            <td>{{ $demande->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-{{
                                    $demande->statut == 'accepte' ? 'success' :
                                    ($demande->statut == 'refuse' ? 'danger' : 'warning')
                                }}">
                                    {{ ucfirst($demande->statut) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('enseignant.demandes.show', $demande) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <p class="mb-0">Aucune demande reçue</p>
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
