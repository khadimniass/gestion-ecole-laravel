@extends('layouts.app')

@section('title', 'Créer un Utilisateur')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h1 class="h3"><i class="fas fa-user-plus"></i> Créer un Utilisateur</h1>
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
            <h5 class="mb-0"><i class="fas fa-user"></i> Informations de l'utilisateur</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
                @csrf

                <!-- Informations de base -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">
                            Nom complet <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">
                            Mot de passe <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               required
                               minlength="8">
                        <small class="form-text text-muted">Minimum 8 caractères</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">
                            Confirmer le mot de passe <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               required
                               minlength="8">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">
                            Rôle <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('role') is-invalid @enderror"
                                id="role"
                                name="role"
                                required>
                            <option value="">-- Sélectionner un rôle --</option>
                            <option value="etudiant" {{ old('role') == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                            <option value="enseignant" {{ old('role') == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                            <option value="coordinateur" {{ old('role') == 'coordinateur' ? 'selected' : '' }}>Coordinateur</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text"
                               class="form-control @error('telephone') is-invalid @enderror"
                               id="telephone"
                               name="telephone"
                               value="{{ old('telephone') }}"
                               placeholder="+221 77 123 45 67">
                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Champs pour Étudiant -->
                <div id="etudiantFields" class="role-specific-fields" style="display: none;">
                    <hr>
                    <h5 class="mb-3"><i class="fas fa-user-graduate"></i> Informations Étudiant</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="matricule" class="form-label">
                                Matricule <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('matricule') is-invalid @enderror"
                                   id="matricule"
                                   name="matricule"
                                   value="{{ old('matricule') }}">
                            @error('matricule')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="niveau_etude" class="form-label">
                                Niveau d'étude <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('niveau_etude') is-invalid @enderror"
                                    id="niveau_etude"
                                    name="niveau_etude">
                                <option value="">-- Sélectionner un niveau --</option>
                                <option value="L1" {{ old('niveau_etude') == 'L1' ? 'selected' : '' }}>Licence 1</option>
                                <option value="L2" {{ old('niveau_etude') == 'L2' ? 'selected' : '' }}>Licence 2</option>
                                <option value="L3" {{ old('niveau_etude') == 'L3' ? 'selected' : '' }}>Licence 3</option>
                                <option value="M1" {{ old('niveau_etude') == 'M1' ? 'selected' : '' }}>Master 1</option>
                                <option value="M2" {{ old('niveau_etude') == 'M2' ? 'selected' : '' }}>Master 2</option>
                            </select>
                            @error('niveau_etude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="filiere_id" class="form-label">
                                Filière <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('filiere_id') is-invalid @enderror"
                                    id="filiere_id"
                                    name="filiere_id">
                                <option value="">-- Sélectionner une filière --</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                                        {{ $filiere->nom }} ({{ $filiere->niveau }})
                                    </option>
                                @endforeach
                            </select>
                            @error('filiere_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Champs pour Enseignant -->
                <div id="enseignantFields" class="role-specific-fields" style="display: none;">
                    <hr>
                    <h5 class="mb-3"><i class="fas fa-chalkboard-teacher"></i> Informations Enseignant</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="departement_enseignant" class="form-label">
                                Département <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('departement') is-invalid @enderror"
                                    id="departement_enseignant"
                                    name="departement">
                                <option value="">-- Sélectionner un département --</option>
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
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="specialite" class="form-label">Spécialité</label>
                            <input type="text"
                                   class="form-control @error('specialite') is-invalid @enderror"
                                   id="specialite"
                                   name="specialite"
                                   value="{{ old('specialite') }}"
                                   placeholder="Ex: Intelligence Artificielle">
                            @error('specialite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Champs pour Coordinateur -->
                <div id="coordinateurFields" class="role-specific-fields" style="display: none;">
                    <hr>
                    <h5 class="mb-3"><i class="fas fa-user-tie"></i> Informations Coordinateur</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="departement_coordinateur" class="form-label">
                                Département <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('departement') is-invalid @enderror"
                                    id="departement_coordinateur"
                                    name="departement">
                                <option value="">-- Sélectionner un département --</option>
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
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <hr>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Créer l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const roleSpecificFields = document.querySelectorAll('.role-specific-fields');

    // Fonction pour afficher/masquer les champs selon le rôle
    function toggleRoleFields() {
        const selectedRole = roleSelect.value;

        // Masquer tous les champs spécifiques
        roleSpecificFields.forEach(field => {
            field.style.display = 'none';
            // Désactiver les champs requis dans les sections masquées
            field.querySelectorAll('input, select').forEach(input => {
                input.removeAttribute('required');
            });
        });

        // Afficher et activer les champs pour le rôle sélectionné
        if (selectedRole === 'etudiant') {
            const etudiantFields = document.getElementById('etudiantFields');
            etudiantFields.style.display = 'block';
            document.getElementById('matricule').setAttribute('required', 'required');
            document.getElementById('niveau_etude').setAttribute('required', 'required');
            document.getElementById('filiere_id').setAttribute('required', 'required');
        } else if (selectedRole === 'enseignant') {
            const enseignantFields = document.getElementById('enseignantFields');
            enseignantFields.style.display = 'block';
            document.getElementById('departement_enseignant').setAttribute('required', 'required');
        } else if (selectedRole === 'coordinateur') {
            const coordinateurFields = document.getElementById('coordinateurFields');
            coordinateurFields.style.display = 'block';
            document.getElementById('departement_coordinateur').setAttribute('required', 'required');
        }
    }

    // Écouter les changements de rôle
    roleSelect.addEventListener('change', toggleRoleFields);

    // Afficher les champs si un rôle est déjà sélectionné (après erreur de validation)
    if (roleSelect.value) {
        toggleRoleFields();
    }

    // Validation du mot de passe
    const form = document.getElementById('createUserForm');
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;

        if (password !== passwordConfirmation) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas !');
            return false;
        }
    });
});
</script>
@endpush
@endsection
