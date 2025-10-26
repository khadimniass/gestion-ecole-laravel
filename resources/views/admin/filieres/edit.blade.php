@extends('layouts.app')

@section('title', 'Modifier la Filière')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h1 class="h3"><i class="fas fa-edit"></i> Modifier la Filière</h1>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <h5 class="alert-heading"><i class="fas fa-exclamation-circle"></i> Erreurs de validation</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Informations de la filière</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.filieres.update', $filiere) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label">
                            Nom de la filière <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('nom') is-invalid @enderror"
                               id="nom"
                               name="nom"
                               value="{{ old('nom', $filiere->nom) }}"
                               required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="code" class="form-label">
                            Code <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('code') is-invalid @enderror"
                               id="code"
                               name="code"
                               value="{{ old('code', $filiere->code) }}"
                               maxlength="10"
                               required>
                        <small class="form-text text-muted">Maximum 10 caractères</small>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="niveau" class="form-label">
                            Niveau <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('niveau') is-invalid @enderror"
                                id="niveau"
                                name="niveau"
                                required>
                            <option value="">-- Sélectionner un niveau --</option>
                            <option value="licence" {{ old('niveau', $filiere->niveau) == 'licence' ? 'selected' : '' }}>Licence</option>
                            <option value="master" {{ old('niveau', $filiere->niveau) == 'master' ? 'selected' : '' }}>Master</option>
                        </select>
                        @error('niveau')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="departement" class="form-label">
                            Département <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('departement') is-invalid @enderror"
                                id="departement"
                                name="departement"
                                required>
                            <option value="">-- Sélectionner un département --</option>
                            <option value="Informatique" {{ old('departement', $filiere->departement) == 'Informatique' ? 'selected' : '' }}>Informatique</option>
                            <option value="Mathématiques" {{ old('departement', $filiere->departement) == 'Mathématiques' ? 'selected' : '' }}>Mathématiques</option>
                            <option value="Physique" {{ old('departement', $filiere->departement) == 'Physique' ? 'selected' : '' }}>Physique</option>
                            <option value="Chimie" {{ old('departement', $filiere->departement) == 'Chimie' ? 'selected' : '' }}>Chimie</option>
                            <option value="Biologie" {{ old('departement', $filiere->departement) == 'Biologie' ? 'selected' : '' }}>Biologie</option>
                            <option value="Génie Civil" {{ old('departement', $filiere->departement) == 'Génie Civil' ? 'selected' : '' }}>Génie Civil</option>
                            <option value="Génie Électrique" {{ old('departement', $filiere->departement) == 'Génie Électrique' ? 'selected' : '' }}>Génie Électrique</option>
                            <option value="Génie Mécanique" {{ old('departement', $filiere->departement) == 'Génie Mécanique' ? 'selected' : '' }}>Génie Mécanique</option>
                        </select>
                        @error('departement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="active" class="form-label">Statut</label>
                        <select class="form-select @error('active') is-invalid @enderror"
                                id="active"
                                name="active">
                            <option value="1" {{ old('active', $filiere->active) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('active', $filiere->active) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              name="description"
                              rows="4">{{ old('description', $filiere->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.filieres.show', $filiere) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
