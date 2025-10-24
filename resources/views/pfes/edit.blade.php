@extends('layouts.app')

@section('title', 'Modifier le PFE')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-edit"></i> Modifier le PFE</h1>
        <a href="{{ route('pfes.show', $pfe) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('enseignant.pfes.update', $pfe) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" class="form-control @error('date_debut') is-invalid @enderror"
                               id="date_debut" name="date_debut"
                               value="{{ old('date_debut', $pfe->date_debut->format('Y-m-d')) }}">
                        @error('date_debut')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="date_fin_prevue" class="form-label">Date de fin prévue</label>
                        <input type="date" class="form-control @error('date_fin_prevue') is-invalid @enderror"
                               id="date_fin_prevue" name="date_fin_prevue"
                               value="{{ old('date_fin_prevue', $pfe->date_fin_prevue->format('Y-m-d')) }}">
                        @error('date_fin_prevue')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="statut" class="form-label">Statut</label>
                        <select class="form-select @error('statut') is-invalid @enderror"
                                id="statut" name="statut">
                            <option value="en_cours" {{ old('statut', $pfe->statut) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="termine" {{ old('statut', $pfe->statut) == 'termine' ? 'selected' : '' }}>Terminé</option>
                            <option value="abandonne" {{ old('statut', $pfe->statut) == 'abandonne' ? 'selected' : '' }}>Abandonné</option>
                            <option value="reporte" {{ old('statut', $pfe->statut) == 'reporte' ? 'selected' : '' }}>Reporté</option>
                        </select>
                        @error('statut')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="note_finale" class="form-label">Note finale (/20)</label>
                        <input type="number" step="0.01" min="0" max="20"
                               class="form-control @error('note_finale') is-invalid @enderror"
                               id="note_finale" name="note_finale"
                               value="{{ old('note_finale', $pfe->note_finale) }}">
                        @error('note_finale')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="date_soutenance" class="form-label">Date de soutenance</label>
                        <input type="date" class="form-control @error('date_soutenance') is-invalid @enderror"
                               id="date_soutenance" name="date_soutenance"
                               value="{{ old('date_soutenance', $pfe->date_soutenance?->format('Y-m-d')) }}">
                        @error('date_soutenance')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="heure_soutenance" class="form-label">Heure de soutenance</label>
                        <input type="time" class="form-control @error('heure_soutenance') is-invalid @enderror"
                               id="heure_soutenance" name="heure_soutenance"
                               value="{{ old('heure_soutenance', $pfe->heure_soutenance) }}">
                        @error('heure_soutenance')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="salle_soutenance" class="form-label">Salle de soutenance</label>
                        <input type="text" class="form-control @error('salle_soutenance') is-invalid @enderror"
                               id="salle_soutenance" name="salle_soutenance"
                               value="{{ old('salle_soutenance', $pfe->salle_soutenance) }}">
                        @error('salle_soutenance')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="observations" class="form-label">Observations</label>
                        <textarea class="form-control @error('observations') is-invalid @enderror"
                                  id="observations" name="observations" rows="4">{{ old('observations', $pfe->observations) }}</textarea>
                        @error('observations')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('pfes.show', $pfe) }}" class="btn btn-secondary">
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
