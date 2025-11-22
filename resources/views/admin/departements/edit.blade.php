@extends('layouts.app')

@section('title', 'Modifier un Département')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-building"></i> Modifier un Département</h2>
        <a href="{{ route('admin.departements.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.departements.update', $departement) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">
                            Code <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('code') is-invalid @enderror"
                               id="code"
                               name="code"
                               value="{{ old('code', $departement->code) }}"
                               placeholder="Ex: INFO"
                               required
                               maxlength="10">
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Code court du département (max 10 caractères)</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label">
                            Nom <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('nom') is-invalid @enderror"
                               id="nom"
                               name="nom"
                               value="{{ old('nom', $departement->nom) }}"
                               placeholder="Ex: Informatique"
                               required
                               maxlength="100">
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              name="description"
                              rows="4"
                              placeholder="Description du département (optionnel)">{{ old('description', $departement->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox"
                           class="form-check-input"
                           id="actif"
                           name="actif"
                           value="1"
                           {{ old('actif', $departement->actif) ? 'checked' : '' }}>
                    <label class="form-check-label" for="actif">
                        Département actif
                    </label>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.departements.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
