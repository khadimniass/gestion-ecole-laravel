@extends('layouts.app')

@section('title', 'Modifier l\'Utilisateur')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h1 class="h3"><i class="fas fa-user-edit"></i> Modifier l'Utilisateur</h1>
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
            <form action="{{ route('admin.users.update', $user) }}" method="POST" id="editUserForm">
                @csrf
                @method('PUT')

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
                               value="{{ old('name', $user->name) }}"
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
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                            <option value="etudiant" {{ old('role', $user->role) == 'etudiant' ? 'selected' : '' }}>Étudiant</option>
                            <option value="enseignant" {{ old('role', $user->role) == 'enseignant' ? 'selected' : '' }}>Enseignant</option>
                            <option value="coordinateur" {{ old('role', $user->role) == 'coordinateur' ? 'selected' : '' }}>Coordinateur</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
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
                               value="{{ old('telephone', $user->telephone) }}"
                               placeholder="+221 77 123 45 67">
                        @error('telephone')
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
                            <option value="1" {{ old('active', $user->active) == 1 ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ old('active', $user->active) == 0 ? 'selected' : '' }}>Inactif</option>
                        </select>
                        @error('active')
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
                            <label for="matricule" class="form-label">Matricule</label>
                            <input type="text"
                                   class="form-control @error('matricule') is-invalid @enderror"
                                   id="matricule"
                                   name="matricule"
                                   value="{{ old('matricule', $user->matricule) }}">
                            @error('matricule')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="niveau_etude" class="form-label">Niveau d'étude</label>
                            <select class="form-select @error('niveau_etude') is-invalid @enderror"
                                    id="niveau_etude"
                                    name="niveau_etude">
                                <option value="">-- Sélectionner un niveau --</option>
                                <option value="L1" {{ old('niveau_etude', $user->niveau_etude) == 'L1' ? 'selected' : '' }}>Licence 1</option>
                                <option value="L2" {{ old('niveau_etude', $user->niveau_etude) == 'L2' ? 'selected' : '' }}>Licence 2</option>
                                <option value="L3" {{ old('niveau_etude', $user->niveau_etude) == 'L3' ? 'selected' : '' }}>Licence 3</option>
                                <option value="M1" {{ old('niveau_etude', $user->niveau_etude) == 'M1' ? 'selected' : '' }}>Master 1</option>
                                <option value="M2" {{ old('niveau_etude', $user->niveau_etude) == 'M2' ? 'selected' : '' }}>Master 2</option>
                            </select>
                            @error('niveau_etude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="filiere_id" class="form-label">Filière</label>
                            <select class="form-select @error('filiere_id') is-invalid @enderror"
                                    id="filiere_id"
                                    name="filiere_id">
                                <option value="">-- Sélectionner une filière --</option>
                                @foreach($filieres as $filiere)
                                    <option value="{{ $filiere->id }}" {{ old('filiere_id', $user->filiere_id) == $filiere->id ? 'selected' : '' }}>
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
                            <label for="departement_enseignant" class="form-label">Département</label>
                            <select class="form-select @error('departement') is-invalid @enderror"
                                    id="departement_enseignant"
                                    name="departement">
                                <option value="">-- Sélectionner un département --</option>
                                <option value="Informatique" {{ old('departement', $user->departement) == 'Informatique' ? 'selected' : '' }}>Informatique</option>
                                <option value="Mathématiques" {{ old('departement', $user->departement) == 'Mathématiques' ? 'selected' : '' }}>Mathématiques</option>
                                <option value="Physique" {{ old('departement', $user->departement) == 'Physique' ? 'selected' : '' }}>Physique</option>
                                <option value="Chimie" {{ old('departement', $user->departement) == 'Chimie' ? 'selected' : '' }}>Chimie</option>
                                <option value="Biologie" {{ old('departement', $user->departement) == 'Biologie' ? 'selected' : '' }}>Biologie</option>
                                <option value="Génie Civil" {{ old('departement', $user->departement) == 'Génie Civil' ? 'selected' : '' }}>Génie Civil</option>
                                <option value="Génie Électrique" {{ old('departement', $user->departement) == 'Génie Électrique' ? 'selected' : '' }}>Génie Électrique</option>
                                <option value="Génie Mécanique" {{ old('departement', $user->departement) == 'Génie Mécanique' ? 'selected' : '' }}>Génie Mécanique</option>
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
                                   value="{{ old('specialite', $user->specialite) }}"
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
                            <label for="departement_coordinateur" class="form-label">Département</label>
                            <select class="form-select @error('departement') is-invalid @enderror"
                                    id="departement_coordinateur"
                                    name="departement">
                                <option value="">-- Sélectionner un département --</option>
                                <option value="Informatique" {{ old('departement', $user->departement) == 'Informatique' ? 'selected' : '' }}>Informatique</option>
                                <option value="Mathématiques" {{ old('departement', $user->departement) == 'Mathématiques' ? 'selected' : '' }}>Mathématiques</option>
                                <option value="Physique" {{ old('departement', $user->departement) == 'Physique' ? 'selected' : '' }}>Physique</option>
                                <option value="Chimie" {{ old('departement', $user->departement) == 'Chimie' ? 'selected' : '' }}>Chimie</option>
                                <option value="Biologie" {{ old('departement', $user->departement) == 'Biologie' ? 'selected' : '' }}>Biologie</option>
                                <option value="Génie Civil" {{ old('departement', $user->departement) == 'Génie Civil' ? 'selected' : '' }}>Génie Civil</option>
                                <option value="Génie Électrique" {{ old('departement', $user->departement) == 'Génie Électrique' ? 'selected' : '' }}>Génie Électrique</option>
                                <option value="Génie Mécanique" {{ old('departement', $user->departement) == 'Génie Mécanique' ? 'selected' : '' }}>Génie Mécanique</option>
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
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
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
        });

        // Afficher les champs pour le rôle sélectionné
        if (selectedRole === 'etudiant') {
            document.getElementById('etudiantFields').style.display = 'block';
        } else if (selectedRole === 'enseignant') {
            document.getElementById('enseignantFields').style.display = 'block';
        } else if (selectedRole === 'coordinateur') {
            document.getElementById('coordinateurFields').style.display = 'block';
        }
    }

    // Écouter les changements de rôle
    roleSelect.addEventListener('change', toggleRoleFields);

    // Afficher les champs au chargement
    toggleRoleFields();
});
</script>
@endpush
@endsection
