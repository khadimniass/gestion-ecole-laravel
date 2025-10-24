@extends('layouts.app')

@section('title', 'Nouvelle Demande d\'Encadrement')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><i class="fas fa-envelope"></i> Nouvelle Demande d'Encadrement</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('etudiant.demandes.store') }}" method="POST" id="demandeForm">
                        @csrf

                        <!-- Choix de l'enseignant -->
                        <div class="mb-3">
                            <label for="enseignant_id" class="form-label">
                                <i class="fas fa-user-tie"></i> Enseignant Encadrant <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('enseignant_id') is-invalid @enderror"
                                    id="enseignant_id" name="enseignant_id" required>
                                <option value="">-- Choisir un enseignant --</option>
                                @foreach($enseignants as $enseignant)
                                    <option value="{{ $enseignant->id }}"
                                        {{ old('enseignant_id') == $enseignant->id ? 'selected' : '' }}>
                                        {{ $enseignant->name }}
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
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-question-circle"></i> Type de Demande <span class="text-danger">*</span>
                            </label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type_demande"
                                           id="type_existant" value="sujet_existant"
                                        {{ old('type_demande', 'sujet_existant') == 'sujet_existant' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_existant">
                                        Choisir un sujet existant
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="type_demande"
                                           id="type_proposition" value="proposition_sujet"
                                        {{ old('type_demande') == 'proposition_sujet' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="type_proposition">
                                        Proposer mon propre sujet
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Sujet existant -->
                        <div id="sujet_existant_div" class="mb-3">
                            <label for="sujet_id" class="form-label">
                                <i class="fas fa-book"></i> Sujet <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('sujet_id') is-invalid @enderror"
                                    id="sujet_id" name="sujet_id">
                                <option value="">-- Choisir un sujet --</option>
                                @foreach($sujets as $sujet)
                                    <option value="{{ $sujet->id }}"
                                            {{ old('sujet_id') == $sujet->id ? 'selected' : '' }}
                                            data-description="{{ $sujet->description }}">
                                        {{ $sujet->titre }} ({{ $sujet->proposePar->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('sujet_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Description du sujet sélectionné -->
                            <div id="sujet_description" class="mt-2 p-3 bg-light rounded" style="display: none;">
                                <strong>Description:</strong>
                                <p id="description_text" class="mb-0"></p>
                            </div>
                        </div>

                        <!-- Proposition de sujet -->
                        <div id="proposition_sujet_div" style="display: none;">
                            <div class="mb-3">
                                <label for="sujet_propose" class="form-label">
                                    <i class="fas fa-lightbulb"></i> Titre du Sujet Proposé <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('sujet_propose') is-invalid @enderror"
                                       id="sujet_propose" name="sujet_propose"
                                       value="{{ old('sujet_propose') }}"
                                       placeholder="Ex: Développement d'une application mobile...">
                                @error('sujet_propose')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description_sujet" class="form-label">
                                    <i class="fas fa-align-left"></i> Description du Sujet <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('description_sujet') is-invalid @enderror"
                                          id="description_sujet" name="description_sujet" rows="4"
                                          placeholder="Décrivez votre projet en détail...">{{ old('description_sujet') }}</textarea>
                                @error('description_sujet')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Lettre de motivation -->
                        <div class="mb-3">
                            <label for="motivation" class="form-label">
                                <i class="fas fa-pen"></i> Lettre de Motivation <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('motivation') is-invalid @enderror"
                                      id="motivation" name="motivation" rows="5" required
                                      placeholder="Expliquez pourquoi vous souhaitez travailler sur ce sujet et avec cet enseignant...">{{ old('motivation') }}</textarea>
                            @error('motivation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 50 caractères</small>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('etudiant.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Envoyer la Demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du type de demande
            const typeExistant = document.getElementById('type_existant');
            const typeProposition = document.getElementById('type_proposition');
            const sujetExistantDiv = document.getElementById('sujet_existant_div');
            const propositionSujetDiv = document.getElementById('proposition_sujet_div');

            function toggleTypeDemande() {
                if (typeExistant.checked) {
                    sujetExistantDiv.style.display = 'block';
                    propositionSujetDiv.style.display = 'none';
                    document.getElementById('sujet_id').required = true;
                    document.getElementById('sujet_propose').required = false;
                    document.getElementById('description_sujet').required = false;
                } else {
                    sujetExistantDiv.style.display = 'none';
                    propositionSujetDiv.style.display = 'block';
                    document.getElementById('sujet_id').required = false;
                    document.getElementById('sujet_propose').required = true;
                    document.getElementById('description_sujet').required = true;
                }
            }

            typeExistant.addEventListener('change', toggleTypeDemande);
            typeProposition.addEventListener('change', toggleTypeDemande);
            toggleTypeDemande();

            // Afficher la description du sujet sélectionné
            const sujetSelect = document.getElementById('sujet_id');
            const sujetDescriptionDiv = document.getElementById('sujet_description');
            const descriptionText = document.getElementById('description_text');

            sujetSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    descriptionText.textContent = selectedOption.dataset.description;
                    sujetDescriptionDiv.style.display = 'block';
                } else {
                    sujetDescriptionDiv.style.display = 'none';
                }
            });
        });
    </script>
@endpush
