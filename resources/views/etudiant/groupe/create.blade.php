@extends('layouts.app')

@section('title', 'Créer un Groupe')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-users"></i> Créer un Groupe</h1>
        <a href="{{ route('etudiant.groupe.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('etudiant.groupe.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nom_groupe" class="form-label">Nom du groupe <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nom_groupe') is-invalid @enderror"
                           id="nom_groupe" name="nom_groupe" value="{{ old('nom_groupe') }}" required>
                    @error('nom_groupe')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nombre_membres" class="form-label">Nombre de membres (incluant vous) <span class="text-danger">*</span></label>
                    <select class="form-select @error('nombre_membres') is-invalid @enderror"
                            id="nombre_membres" name="nombre_membres" required>
                        <option value="1" {{ old('nombre_membres') == 1 ? 'selected' : '' }}>1 (seul)</option>
                        <option value="2" {{ old('nombre_membres') == 2 ? 'selected' : '' }}>2</option>
                        <option value="3" {{ old('nombre_membres') == 3 ? 'selected' : '' }}>3</option>
                    </select>
                    @error('nombre_membres')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Vous serez automatiquement désigné comme chef de groupe.
                    Vous pourrez inviter d'autres membres après la création.
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('etudiant.groupe.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Créer le groupe
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
