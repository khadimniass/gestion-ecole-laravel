<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users (Admin only)
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::with('filiere');

        // Filtres
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('departement')) {
            $query->where('departement', $request->departement);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('matricule', 'like', "%$search%");
            });
        }

        $users = $query->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $filieres = Filiere::actives()->get();

        return view('admin.users.create', compact('filieres'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,coordinateur,enseignant,etudiant',
            'matricule' => 'required_if:role,etudiant|nullable|string|unique:users',
            'departement' => 'required_if:role,enseignant,coordinateur|nullable|string',
            'filiere_id' => 'required_if:role,etudiant|nullable|exists:filieres,id',
            'niveau_etude' => 'required_if:role,etudiant|nullable|in:L1,L2,L3,M1,M2',
            'telephone' => 'nullable|string|max:20',
            'specialite' => 'nullable|string|max:100',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['active'] = true;

        $user = User::create($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->load(['filiere', 'pfesEtudiant', 'pfesEncadrés', 'sujetsProposés']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $filieres = Filiere::actives()->get();

        return view('admin.users.edit', compact('user', 'filieres'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,coordinateur,enseignant,etudiant',
            'matricule' => ['nullable', 'string',
                Rule::unique('users')->ignore($user->id)],
            'departement' => 'nullable|string',
            'filiere_id' => 'nullable|exists:filieres,id',
            'niveau_etude' => 'nullable|in:L1,L2,L3,M1,M2',
            'telephone' => 'nullable|string|max:20',
            'specialite' => 'nullable|string|max:100',
            'active' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        // Vérifier qu'il n'y a pas de données liées
        if ($user->pfesEncadrés()->exists() || $user->pfesEtudiant()->exists()) {
            return back()->with('error', 'Impossible de supprimer un utilisateur avec des PFE.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = Auth::user();
        $user->load(['filiere', 'pfesEtudiant.sujet', 'pfesEncadrés.sujet']);
        return view('profile.index', compact('user'));
    }

    /**
     * Edit user profile
     */
    public function editProfile()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id)],
            'telephone' => 'nullable|string|max:20',
            'specialite' => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        return redirect()->route('profile.index')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }

    /**
     * Search students (for AJAX)
     */
    public function searchEtudiants(Request $request)
    {
        $query = $request->get('q');

        $etudiants = User::where('role', 'etudiant')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                    ->orWhere('matricule', 'like', "%$query%");
            })
            ->where('active', true)
            ->limit(10)
            ->get(['id', 'name', 'matricule', 'email']);

        return response()->json($etudiants);
    }

    /**
     * Check matricule availability (for AJAX)
     */
    public function checkMatricule(Request $request)
    {
        $exists = User::where('matricule', $request->matricule)->exists();

        return response()->json(['available' => !$exists]);
    }

    /**
     * Check email availability (for AJAX)
     */
    public function checkEmail(Request $request)
    {
        $exists = User::where('email', $request->email)
            ->where('id', '!=', $request->user_id)
            ->exists();

        return response()->json(['available' => !$exists]);
    }
}
