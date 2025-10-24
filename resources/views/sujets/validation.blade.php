@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clipboard-check"></i> Validation des Sujets PFE</h2>
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

    @if($sujets->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Aucun sujet en attente de validation.
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-list"></i> Sujets en attente ({{ $sujets->total() }})</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Titre</th>
                                <th>Proposé par</th>
                                <th>Filière</th>
                                <th>Niveau</th>
                                <th>Nombre d'étudiants</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sujets as $sujet)
                                <tr>
                                    <td>
                                        <strong>{{ $sujet->titre }}</strong>
                                        @if($sujet->motsCles->isNotEmpty())
                                            <br>
                                            <small>
                                                @foreach($sujet->motsCles as $motCle)
                                                    <span class="badge bg-secondary">{{ $motCle->mot }}</span>
                                                @endforeach
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fas fa-user"></i> {{ $sujet->proposePar->name }}
                                        <br>
                                        <small class="text-muted">{{ $sujet->departement }}</small>
                                    </td>
                                    <td>
                                        @if($sujet->filiere)
                                            <span class="badge bg-info">{{ $sujet->filiere->nom }}</span>
                                        @else
                                            <span class="badge bg-secondary">Toutes</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($sujet->niveau_requis === 'tous')
                                            <span class="badge bg-secondary">Tous</span>
                                        @else
                                            <span class="badge bg-primary">{{ ucfirst($sujet->niveau_requis) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-dark">{{ $sujet->nombre_etudiants_max }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $sujet->created_at->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('sujets.show', $sujet) }}"
                                               class="btn btn-sm btn-info"
                                               title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('sujets.valider', $sujet) }}"
                                                  method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn btn-sm btn-success"
                                                        title="Valider"
                                                        onclick="return confirm('Confirmer la validation de ce sujet ?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    title="Rejeter"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejetModal{{ $sujet->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal pour le rejet -->
                                <div class="modal fade" id="rejetModal{{ $sujet->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('sujets.rejeter', $sujet) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Rejeter le sujet</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Sujet :</strong> {{ $sujet->titre }}</p>
                                                    <div class="mb-3">
                                                        <label for="motif{{ $sujet->id }}" class="form-label">
                                                            Motif du rejet <span class="text-danger">*</span>
                                                        </label>
                                                        <textarea name="motif"
                                                                  id="motif{{ $sujet->id }}"
                                                                  class="form-control"
                                                                  rows="4"
                                                                  required
                                                                  placeholder="Expliquez la raison du rejet..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        Annuler
                                                    </button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-times"></i> Rejeter
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            {{ $sujets->links() }}
        </div>
    @endif
</div>
@endsection
