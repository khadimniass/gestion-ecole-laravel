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
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf

                        <div class="mb-3">
                            <label for="role" class="form-label">Type de compte <span class="text-danger">*</span></label>
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
                            <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Champs pour Étudiant -->
                        <div id="etudiant-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="matricule" class="form-label">Matricule <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('matricule') is-invalid @enderror"
                                       id="matricule"
                                       name="matricule"
                                       value="{{ old('matricule') }}"
                                       placeholder="Ex: C98363"
                                       pattern="C\d{5}"
                                       maxlength="6">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Format: C suivi de 5 chiffres (ex: C98363, C73652)
                                </small>
                                @error('matricule')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="filiere_id" class="form-label">Filière <span class="text-danger">*</span></label>
                                <select class="form-select @error('filiere_id') is-invalid @enderror"
                                        id="filiere_id" name="filiere_id">
                                    <option value="">-- Choisir une filière --</option>
                                    @foreach($filieres as $filiere)
                                        <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                            {{ $filiere->nom }} ({{ ucfirst($filiere->niveau) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('filiere_id')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="niveau_etude" class="form-label">Niveau d'études <span class="text-danger">*</span></label>
                                <select class="form-select @error('niveau_etude') is-invalid @enderror"
                                        id="niveau_etude" name="niveau_etude">
                                    <option value="">-- Choisir un niveau --</option>
                                    <option value="licence" {{ old('niveau_etude') == 'licence' ? 'selected' : '' }}>Licence</option>
                                    <option value="master" {{ old('niveau_etude') == 'master' ? 'selected' : '' }}>Master</option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Seuls les étudiants en Licence 3 ou Master 2 peuvent faire un PFE
                                </small>
                                @error('niveau_etude')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Champs pour Enseignant -->
                        <div id="enseignant-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="departement" class="form-label">Département <span class="text-danger">*</span></label>
                                <select class="form-select @error('departement') is-invalid @enderror"
                                        id="departement" name="departement">
                                    <option value="">-- Choisir un département --</option>
                                    <option value="Informatique" {{ old('departement') == 'Informatique' ? 'selected' : '' }}>Informatique</option>
                                    <option value="Mathématiques" {{ old('departement') == 'Mathématiques' ? 'selected' : '' }}>Mathématiques</option>
                                    <option value="Physique" {{ old('departement') == 'Physique' ? 'selected' : '' }}>Physique</option>
                                    <option value="Chimie" {{ old('departement') == 'Chimie' ? 'selected' : '' }}>Chimie</option>
                                    <option value="Biologie" {{ old('departement') == 'Biologie' ? 'selected' : '' }}>Biologie</option>
                                    <option value="Génie Civil" {{ old('departement') == 'Génie Civil' ? 'selected' : '' }}>Génie Civil</option>
                                    <option value="Génie Électrique" {{ old('departement') == 'Génie Électrique' ? 'selected' : '' }}>Génie Électrique</option>
                                    <option value="Génie Mécanique" {{ old('departement') == 'Génie Mécanique' ? 'selected' : '' }}>Génie Mécanique</option>
                                </select>
                                @error('departement')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="specialite" class="form-label">Spécialité (optionnel)</label>
                                <input type="text" class="form-control @error('specialite') is-invalid @enderror"
                                       id="specialite" name="specialite" value="{{ old('specialite') }}"
                                       placeholder="Ex: Intelligence Artificielle">
                                @error('specialite')
                                <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="telephone" class="form-label">Téléphone (optionnel)</label>
                            <input type="text" class="form-control @error('telephone') is-invalid @enderror"
                                   id="telephone" name="telephone" value="{{ old('telephone') }}"
                                   placeholder="+221 77 123 45 67">
                            @error('telephone')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required minlength="8">
                            <small class="form-text text-muted">Minimum 8 caractères</small>
                            @error('password')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" required minlength="8">
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
            
            // Champs requis selon le rôle
            const etudiantInputs = ['matricule', 'filiere_id', 'niveau_etude'];
            const enseignantInputs = ['departement'];

            function toggleFields() {
                const role = roleSelect.value;
                
                // Masquer tous les champs
                etudiantFields.style.display = 'none';
                enseignantFields.style.display = 'none';
                
                // Désactiver tous les champs requis
                etudiantInputs.forEach(id => {
                    const input = document.getElementById(id);
                    if (input) input.removeAttribute('required');
                });
                enseignantInputs.forEach(id => {
                    const input = document.getElementById(id);
                    if (input) input.removeAttribute('required');
                });

                // Afficher et activer les champs selon le rôle
                if (role === 'etudiant') {
                    etudiantFields.style.display = 'block';
                    etudiantInputs.forEach(id => {
                        const input = document.getElementById(id);
                        if (input) input.setAttribute('required', 'required');
                    });
                } else if (role === 'enseignant') {
                    enseignantFields.style.display = 'block';
                    enseignantInputs.forEach(id => {
                        const input = document.getElementById(id);
                        if (input) input.setAttribute('required', 'required');
                    });
                }
            }

            roleSelect.addEventListener('change', toggleFields);
            
            // Initialiser l'affichage au chargement
            toggleFields();
        });
    </script>
@endpush
