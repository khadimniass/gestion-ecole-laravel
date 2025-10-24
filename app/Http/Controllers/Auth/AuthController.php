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
            'matricule' => ['required_if:role,etudiant', 'string', 'unique:users'],
            'role' => ['required', 'in:etudiant,enseignant'],
            'telephone' => ['nullable', 'string'],
            'departement' => ['required_if:role,enseignant', 'string'],
            'filiere_id' => ['required_if:role,etudiant', 'exists:filieres,id'],
            'niveau_etude' => ['required_if:role,etudiant', 'in:L1,L2,L3,M1,M2'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'matricule' => $validated['matricule'] ?? null,
            'role' => $validated['role'],
            'telephone' => $validated['telephone'] ?? null,
            'departement' => $validated['departement'] ?? null,
            'filiere_id' => $validated['filiere_id'] ?? null,
            'niveau_etude' => $validated['niveau_etude'] ?? null,
        ]);

        Auth::login($user);

        return redirect()->route($user->role . '.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
