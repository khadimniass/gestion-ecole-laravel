@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fas fa-user-plus"></i> Inscription</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="role" class="form-label">Type de compte</label>
                            <select class="form-select @error('role') is-invalid @enderror"
                                    id="role" name="role" required>
                                <option value="">-- Choisir --</option>
                                <option value="etudiant" {{ old('role') == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                                <option value="enseignant" {{ old('role') == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                            </select>
                            @error('role')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Champs pour Étudiant -->
                        <div id="etudiant-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="matricule" class="form-label">Matricule</label>
                                <input type="text" class="form-control @error('matricule') is-invalid @enderror"
                                       id="matricule" name="matricule" value="{{ old('matricule') }}">
                                @error('matricule')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="filiere_id" class="form-label">Filière</label>
                                <select class="form-select @error('filiere_id') is-invalid @enderror"
                                        id="filiere_id" name="filiere_id">
                                    <option value="">-- Choisir --</option>
                                    @foreach($filieres as $filiere)
                                        <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                            {{ $filiere->nom }} ({{ $filiere->niveau }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('filiere_id')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="niveau_etude" class="form-label">Niveau d'études</label>
                                <select class="form-select @error('niveau_etude') is-invalid @enderror"
                                        id="niveau_etude" name="niveau_etude">
                                    <option value="">-- Choisir --</option>
                                    <option value="L1" {{ old('niveau_etude') == 'L1' ? 'selected' : '' }}>L1</option>
                                    <option value="L2" {{ old('niveau_etude') == 'L2' ? 'selected' : '' }}>L2</option>
                                    <option value="L3" {{ old('niveau_etude') == 'L3' ? 'selected' : '' }}>L3</option>
                                    <option value="M1" {{ old('niveau_etude') == 'M1' ? 'selected' : '' }}>M1</option>
                                    <option value="M2" {{ old('niveau_etude') == 'M2' ? 'selected' : '' }}>M2</option>
                                </select>
                                @error('niveau_etude')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Champs pour Enseignant -->
                        <div id="enseignant-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="departement" class="form-label">Département</label>
                                <input type="text" class="form-control @error('departement') is-invalid @enderror"
                                       id="departement" name="departement" value="{{ old('departement', 'Informatique') }}">
                                @error('departement')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="telephone" class="form-label">Téléphone (optionnel)</label>
                            <input type="text" class="form-control @error('telephone') is-invalid @enderror"
                                   id="telephone" name="telephone" value="{{ old('telephone') }}">
                            @error('telephone')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required>
                            @error('password')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> S'inscrire
                            </button>
                        </div>
                    </form>

                    <hr>

                    <div class="text-center">
                        <p class="mb-0">Déjà inscrit ?</p>
                        <a href="{{ route('login') }}" class="btn btn-link">Se connecter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const etudiantFields = document.getElementById('etudiant-fields');
            const enseignantFields = document.getElementById('enseignant-fields');

            function toggleFields() {
                if (roleSelect.value === 'etudiant') {
                    etudiantFields.style.display = 'block';
                    enseignantFields.style.display = 'none';
                } else if (roleSelect.value === 'enseignant') {
                    etudiantFields.style.display = 'none';
                    enseignantFields.style.display = 'block';
                } else {
                    etudiantFields.style.display = 'none';
                    enseignantFields.style.display = 'none';
                }
            }

            roleSelect.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
@endpush
