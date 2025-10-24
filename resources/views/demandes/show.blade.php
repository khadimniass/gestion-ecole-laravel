@extends('layouts.app')

@section('title', 'Détails de la Demande')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-envelope"></i> Détails de la Demande</h1>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informations de la demande</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong class="text-muted">Étudiant :</strong>
                        <p>{{ $demande->etudiant->name }} ({{ $demande->etudiant->matricule }})</p>
                    </div>

                    <div class="mb-3">
                        <strong class="text-muted">Enseignant :</strong>
                        <p>{{ $demande->enseignant->name }}</p>
                    </div>

                    @if($demande->sujet)
                        <div class="mb-3">
                            <strong class="text-muted">Sujet :</strong>
                            <p>
                                <a href="{{ route('sujets.show', $demande->sujet) }}">
                                    {{ $demande->sujet->titre }}
                                </a>
                            </p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong class="text-muted">Message :</strong>
                        <p>{{ $demande->message }}</p>
                    </div>

                    @if($demande->reponse)
                        <div class="mb-3">
                            <strong class="text-muted">Réponse :</strong>
                            <p>{{ $demande->reponse }}</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong class="text-muted">Date de demande :</strong>
                        <p>{{ $demande->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-flag"></i> Statut</h5>
                </div>
                <div class="card-body text-center">
                    <span class="badge bg-{{
                        $demande->statut == 'accepte' ? 'success' :
                        ($demande->statut == 'refuse' ? 'danger' : 'warning')
                    }} fs-5">
                        {{ ucfirst($demande->statut) }}
                    </span>
                </div>
            </div>

            @if($demande->statut == 'en_attente' && auth()->user()->id == $demande->enseignant_id)
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-cog"></i> Actions</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('enseignant.demandes.accepter', $demande) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check"></i> Accepter
                            </button>
                        </form>

                        <form action="{{ route('enseignant.demandes.refuser', $demande) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Êtes-vous sûr de vouloir refuser cette demande ?')">
                                <i class="fas fa-times"></i> Refuser
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
