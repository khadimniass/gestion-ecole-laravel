@extends('layouts.app')

@section('title', 'Modifier le Sujet PFE')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-edit"></i> Modifier le Sujet PFE</h1>
        <a href="{{ route('sujets.show', $sujet) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('sujets.update', $sujet) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="titre" class="form-label">Titre du sujet <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('titre') is-invalid @enderror"
                               id="titre" name="titre" value="{{ old('titre', $sujet->titre) }}" required>
                        @error('titre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="5" required>{{ old('description', $sujet->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="objectifs" class="form-label">Objectifs</label>
                        <textarea class="form-control @error('objectifs') is-invalid @enderror"
                                  id="objectifs" name="objectifs" rows="3">{{ old('objectifs', $sujet->objectifs) }}</textarea>
                        @error('objectifs')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="technologies" class="form-label">Technologies</label>
                        <input type="text" class="form-control @error('technologies') is-invalid @enderror"
                               id="technologies" name="technologies" value="{{ old('technologies', $sujet->technologies) }}"
                               placeholder="Ex: Laravel, Vue.js, MySQL">
                        @error('technologies')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="filiere_id" class="form-label">Filière</label>
                        <select class="form-select @error('filiere_id') is-invalid @enderror"
                                id="filiere_id" name="filiere_id">
                            <option value="">Toutes les filières</option>
                            @foreach($filieres as $filiere)
                                <option value="{{ $filiere->id }}"
                                        {{ old('filiere_id', $sujet->filiere_id) == $filiere->id ? 'selected' : '' }}>
                                    {{ $filiere->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('filiere_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="niveau_requis" class="form-label">Niveau requis <span class="text-danger">*</span></label>
                        <select class="form-select @error('niveau_requis') is-invalid @enderror"
                                id="niveau_requis" name="niveau_requis" required>
                            <option value="licence" {{ old('niveau_requis', $sujet->niveau_requis) == 'licence' ? 'selected' : '' }}>Licence</option>
                            <option value="master" {{ old('niveau_requis', $sujet->niveau_requis) == 'master' ? 'selected' : '' }}>Master</option>
                            <option value="tous" {{ old('niveau_requis', $sujet->niveau_requis) == 'tous' ? 'selected' : '' }}>Tous</option>
                        </select>
                        @error('niveau_requis')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nombre_etudiants_max" class="form-label">Nombre d'étudiants max <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('nombre_etudiants_max') is-invalid @enderror"
                               id="nombre_etudiants_max" name="nombre_etudiants_max"
                               value="{{ old('nombre_etudiants_max', $sujet->nombre_etudiants_max) }}" min="1" max="3" required>
                        @error('nombre_etudiants_max')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="mots_cles" class="form-label">Mots-clés (maximum 4)</label>
                        <div id="mots-cles-container">
                            @for($i = 0; $i < 4; $i++)
                                <input type="text" class="form-control mb-2 @error('mots_cles.'.$i) is-invalid @enderror"
                                       name="mots_cles[]" value="{{ old('mots_cles.'.$i, $motsCles[$i] ?? '') }}"
                                       placeholder="Mot-clé {{ $i + 1 }}">
                            @endfor
                        </div>
                        @error('mots_cles')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('sujets.show', $sujet) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
