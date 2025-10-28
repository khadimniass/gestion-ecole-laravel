<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Ajouter la vérification du compte actif
        $credentials['active'] = true;

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirection selon le rôle
            $user = Auth::user();

            if ($user->estAdmin() || $user->estCoordinateur()) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->estEnseignant()) {
                return redirect()->intended('/enseignant/dashboard');
            } else {
                return redirect()->intended('/etudiant/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Les informations d\'identification ne correspondent pas.',
        ])->onlyInput('email');
    }

    public function showRegistrationForm()
    {
        $filieres = Filiere::actives()->get();
        return view('auth.register', compact('filieres'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:etudiant,enseignant'],
            'telephone' => ['nullable', 'string'],
            'departement' => ['required_if:role,enseignant', 'string'],
            'specialite' => ['nullable', 'string', 'max:100'],
            'filiere_id' => ['required_if:role,etudiant', 'exists:filieres,id'],
            'niveau_etude' => ['required_if:role,etudiant', 'in:L1,L2,L3,M1,M2'],
        ]);

        // Générer automatiquement le matricule pour les étudiants
        $matricule = null;
        if ($validated['role'] === 'etudiant') {
            $matricule = $this->genererMatricule($validated['niveau_etude']);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'matricule' => $matricule,
            'role' => $validated['role'],
            'telephone' => $validated['telephone'] ?? null,
            'departement' => $validated['departement'] ?? null,
            'specialite' => $validated['specialite'] ?? null,
            'filiere_id' => $validated['filiere_id'] ?? null,
            'niveau_etude' => $validated['niveau_etude'] ?? null,
            'active' => true,
        ]);

        Auth::login($user);

        return redirect()->route($user->role . '.dashboard')
            ->with('success', 'Inscription réussie ! Bienvenue sur la plateforme.');
    }

    /**
     * Générer un matricule unique pour un étudiant
     */
    private function genererMatricule($niveauEtude)
    {
        $annee = date('Y');
        $niveau = substr($niveauEtude, 0, 1); // L ou M

        // Trouver le dernier matricule de l'année
        $dernierMatricule = User::where('matricule', 'like', "$niveau$annee%")
            ->orderBy('matricule', 'desc')
            ->first();

        if ($dernierMatricule) {
            // Extraire le numéro et l'incrémenter
            $numero = intval(substr($dernierMatricule->matricule, -4)) + 1;
        } else {
            $numero = 1;
        }

        // Format: L20240001 ou M20240001
        return sprintf("%s%s%04d", $niveau, $annee, $numero);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
