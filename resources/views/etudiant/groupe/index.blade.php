@extends('layouts.app')

@section('title', 'Mon Groupe')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-users"></i> Mon Groupe</h1>
        @if(!$monGroupe)
            <a href="{{ route('etudiant.groupe.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Créer un Groupe
            </a>
        @endif
    </div>

    @if($monGroupe)
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-users"></i> {{ $monGroupe->nom_groupe }}
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong class="text-muted">Chef de groupe :</strong>
                    <p>👑 {{ $monGroupe->chefGroupe->name }}</p>
                </div>

                <div class="mb-3">
                    <strong class="text-muted">Membres :</strong>
                    <ul class="list-unstyled">
                        @foreach($monGroupe->membres as $membre)
                            <li class="mb-2">
                                <span class="badge bg-{{
                                    $membre->pivot->statut == 'accepte' ? 'success' :
                                    ($membre->pivot->statut == 'refuse' ? 'danger' : 'warning')
                                }}">
                                    {{ $membre->name }}
                                </span>
                                - {{ ucfirst($membre->pivot->statut) }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mb-3">
                    <strong class="text-muted">Statut du groupe :</strong>
                    <p>
                        <span class="badge bg-{{ $monGroupe->statut == 'complet' ? 'success' : 'warning' }}">
                            {{ ucfirst(str_replace('_', ' ', $monGroupe->statut)) }}
                        </span>
                    </p>
                </div>

                @if(auth()->user()->id == $monGroupe->chef_groupe_id)
                    <div class="mt-4">
                        <h6>Inviter un membre</h6>
                        <form action="{{ route('etudiant.groupe.inviter') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-auto">
                                <input type="email" class="form-control" name="email"
                                       placeholder="Email de l'étudiant" required>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Inviter
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5>Vous n'êtes pas encore dans un groupe</h5>
                <p class="text-muted">Créez un groupe ou attendez une invitation</p>
            </div>
        </div>
    @endif
@endsection
