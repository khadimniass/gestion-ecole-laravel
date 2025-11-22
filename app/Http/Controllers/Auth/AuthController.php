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
            'departement' => ['nullable', 'required_if:role,enseignant', 'string'],
            'specialite' => ['nullable', 'string', 'max:100'],
            'filiere_id' => ['nullable', 'required_if:role,etudiant', 'exists:filieres,id'],
            'niveau_etude' => ['nullable', 'required_if:role,etudiant', 'in:licence,master'],
            'matricule' => ['nullable', 'required_if:role,etudiant', 'string', 'regex:/^C\d{5}$/', 'unique:users,matricule'],
        ], [
            'matricule.required_if' => 'Le matricule est obligatoire pour les étudiants.',
            'matricule.regex' => 'Le matricule doit être au format C suivi de 5 chiffres (ex: C98363).',
            'matricule.unique' => 'Ce matricule existe déjà.',
            'niveau_etude.in' => 'Le niveau doit être Licence ou Master.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'matricule' => $validated['matricule'] ?? null,
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
