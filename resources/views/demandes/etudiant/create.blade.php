@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-paper-plane"></i> Nouvelle Demande d'Encadrement PFE</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('demandes.store') }}" method="POST">
                        @csrf

                        <!-- Choix de l'enseignant -->
                        <div class="mb-4">
                            <label for="enseignant_id" class="form-label">
                                <i class="fas fa-chalkboard-teacher"></i> Enseignant encadrant <span class="text-danger">*</span>
                            </label>
                            <select name="enseignant_id" id="enseignant_id"
                                    class="form-select @error('enseignant_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner un enseignant --</option>
                                @foreach($enseignants as $enseignant)
                                    <option value="{{ $enseignant->id }}" {{ old('enseignant_id') == $enseignant->id ? 'selected' : '' }}>
                                        {{ $enseignant->name }} - {{ $enseignant->departement }}
                                        @if($enseignant->specialite)
                                            ({{ $enseignant->specialite }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('enseignant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type de demande -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-list-alt"></i> Type de demande <span class="text-danger">*</span>
                            </label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type_demande"
                                       id="type_sujet_existant" value="sujet_existant"
                                       {{ old('type_demande') == 'sujet_existant' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_sujet_existant">
                                    <strong>Choisir un sujet existant</strong>
                                    <br><small class="text-muted">Sélectionner parmi les sujets proposés par les enseignants</small>
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="type_demande"
                                       id="type_proposition" value="proposition_sujet"
                                       {{ old('type_demande') == 'proposition_sujet' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_proposition">
                                    <strong>Proposer un nouveau sujet</strong>
                                    <br><small class="text-muted">Soumettre votre propre idée de sujet PFE</small>
                                </label>
                            </div>
                            @error('type_demande')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sujet existant -->
                        <div id="section_sujet_existant" class="mb-4" style="display: none;">
                            <label for="sujet_id" class="form-label">
                                <i class="fas fa-book"></i> Sujet PFE
                            </label>
                            <select name="sujet_id" id="sujet_id" class="form-select @error('sujet_id') is-invalid @enderror">
                                <option value="">-- Sélectionner un sujet --</option>
                                @foreach($sujets as $sujet)
                                    <option value="{{ $sujet->id }}" {{ old('sujet_id') == $sujet->id ? 'selected' : '' }}
                                            data-description="{{ $sujet->description }}">
                                        {{ $sujet->titre }}
                                        @if($sujet->filiere)
                                            - {{ $sujet->filiere->nom }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('sujet_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="sujet_description" class="alert alert-info mt-2" style="display: none;">
                                <strong>Description :</strong>
                                <p class="mb-0 mt-1" id="sujet_description_text"></p>
                            </div>
                        </div>

                        <!-- Proposition de sujet -->
                        <div id="section_proposition_sujet" class="mb-4" style="display: none;">
                            <div class="mb-3">
                                <label for="sujet_propose" class="form-label">
                                    <i class="fas fa-lightbulb"></i> Titre du sujet proposé
                                </label>
                                <input type="text" name="sujet_propose" id="sujet_propose"
                                       class="form-control @error('sujet_propose') is-invalid @enderror"
                                       value="{{ old('sujet_propose') }}"
                                       placeholder="Ex: Développement d'une application de gestion...">
                                @error('sujet_propose')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description_sujet" class="form-label">
                                    <i class="fas fa-align-left"></i> Description détaillée du sujet
                                </label>
                                <textarea name="description_sujet" id="description_sujet" rows="5"
                                          class="form-control @error('description_sujet') is-invalid @enderror"
                                          placeholder="Décrivez votre idée de sujet, les objectifs, les technologies envisagées...">{{ old('description_sujet') }}</textarea>
                                @error('description_sujet')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Motivation -->
                        <div class="mb-4">
                            <label for="motivation" class="form-label">
                                <i class="fas fa-comment-dots"></i> Lettre de motivation <span class="text-danger">*</span>
                                <small class="text-muted">(minimum 50 caractères)</small>
                            </label>
                            <textarea name="motivation" id="motivation" rows="6"
                                      class="form-control @error('motivation') is-invalid @enderror" required
                                      placeholder="Expliquez pourquoi vous souhaitez être encadré par cet enseignant, vos compétences, votre intérêt pour le sujet...">{{ old('motivation') }}</textarea>
                            <small class="text-muted">
                                <span id="motivation_count">0</span> / 50 caractères minimum
                            </small>
                            @error('motivation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('demandes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Envoyer la demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSujetExistant = document.getElementById('type_sujet_existant');
    const typeProposition = document.getElementById('type_proposition');
    const sectionSujetExistant = document.getElementById('section_sujet_existant');
    const sectionProposition = document.getElementById('section_proposition_sujet');
    const sujetSelect = document.getElementById('sujet_id');
    const sujetDescription = document.getElementById('sujet_description');
    const sujetDescriptionText = document.getElementById('sujet_description_text');
    const motivationTextarea = document.getElementById('motivation');
    const motivationCount = document.getElementById('motivation_count');

    // Gestion du type de demande
    function updateSections() {
        if (typeSujetExistant.checked) {
            sectionSujetExistant.style.display = 'block';
            sectionProposition.style.display = 'none';
            sujetSelect.required = true;
            document.getElementById('sujet_propose').required = false;
            document.getElementById('description_sujet').required = false;
        } else if (typeProposition.checked) {
            sectionSujetExistant.style.display = 'none';
            sectionProposition.style.display = 'block';
            sujetSelect.required = false;
            document.getElementById('sujet_propose').required = true;
            document.getElementById('description_sujet').required = true;
        }
    }

    typeSujetExistant.addEventListener('change', updateSections);
    typeProposition.addEventListener('change', updateSections);

    // Initialiser au chargement si déjà sélectionné
    if (typeSujetExistant.checked || typeProposition.checked) {
        updateSections();
    }

    // Afficher la description du sujet sélectionné
    sujetSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const description = selectedOption.dataset.description;

        if (description && description.trim()) {
            sujetDescriptionText.textContent = description;
            sujetDescription.style.display = 'block';
        } else {
            sujetDescription.style.display = 'none';
        }
    });

    // Compteur de caractères pour la motivation
    motivationTextarea.addEventListener('input', function() {
        motivationCount.textContent = this.value.length;

        if (this.value.length < 50) {
            motivationCount.classList.add('text-danger');
            motivationCount.classList.remove('text-success');
        } else {
            motivationCount.classList.add('text-success');
            motivationCount.classList.remove('text-danger');
        }
    });

    // Initialiser le compteur
    motivationCount.textContent = motivationTextarea.value.length;
});
</script>
@endpush
@endsection
